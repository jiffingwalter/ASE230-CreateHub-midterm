<?php
require_once('general.php');
require_once('users.php');
require_once($GLOBALS['readJSONDirectory']);

//get list of all user posts
function get_all_posts(){
    return db->queryAll('SELECT * FROM posts');
}

// return all posts by a specific user by ID -- todo: add order parameter to modify order of returned query
function get_user_posts($user_id){
    return db->preparedQueryAll('SELECT * FROM posts WHERE author=:uid',[
        'uid'=>$user_id
    ]);
}

// return single post by searching by pid in post file
function get_post($pid){
    $post=db->preparedQuery('SELECT * FROM posts WHERE pid=?',[$pid]);
    if (db->resultFound($post)){
        return $post;
    } else {
        display_system_error('Could not find post with ID #'.$pid.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return db->query('SELECT * FROM posts WHERE pid=1'); // return example post to avoid php errors
    }
}

// creates a new post, and attachments/tags if added. returns new post id if made successfully and false if not
function create_post($info_in,$file_in){
    try {
        // initialization
        $isAttachmentProvided=(is_attachment_provided($file_in['attachments']));
        $isTagsProvided=(strlen($info_in['tags'])>0);

        // debug info
        if ($GLOBALS['debug']){
            echo '<pre>incoming data...<br>';var_dump($info_in);var_dump($file_in);echo '</pre>';
        }

        // push post info to database
        db->preparedQuery('INSERT INTO posts (author,title,content,has_attachment,date_created,last_edited)
            VALUES (:author,:title,:content,:has_attachment,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)',[
            'author'=>$info_in['author'],
            'title'=>$info_in['title'],
            'content'=>$info_in['content'],
            'has_attachment'=>($isAttachmentProvided)
        ]);
        // get the new post's id after auto incrementation
        $post_id=db->query('SELECT LAST_INSERT_ID()')['LAST_INSERT_ID()'];

        // handle attachment and push infos to database if one is provided
        if ($isAttachmentProvided) {
            parse_attachments($post_id,$file_in['attachments']);
        }
        // handle tags and push infos to database if any are provided
        if ($isTagsProvided) {
            parse_tags_in($info_in['tags'],$post_id);
        }

        // return post pid if successful
        return $post_id;
    } catch (Throwable $error) {
        display_system_error('Encountered fatal error when creating post',$_SERVER['SCRIPT_NAME']);
        echo '<pre>'.$error.'</pre>';
        return false;
    }
}

// accepts a post and attachment info and edits an existing post, returns pid on success, false if not
function edit_post($info_in,$file_in){ 
    try{
        // debug info
        if ($GLOBALS['debug']){
            echo 'attempting to edit post '.$info_in['pid'].'<br>';
            echo 'incoming data...<br>';var_dump($info_in);var_dump($file_in['attachments']);
        }
        
        // run comparison functions to check what has changed in comparison to the post current in the database
        $updates=compare_post($info_in,$file_in['attachments']);
        $isTextUpdated=$updates['textChanged'];
        $isAttachmentUpdated=$updates['attachmentChanged'];
        $isTagsUpdated=$updates['tagsChanged'];
        $isAuthorUpdated=$updates['authorChanged'];

        // update the post text fields if any text was updated
        if ($isTextUpdated){
            if ($GLOBALS['debug']) echo 'updating post text...';
            db->preparedQuery('UPDATE posts SET title=:title, content=:content WHERE pid=:pid',[
                'title'=>$info_in['title'],
                'content'=>$info_in['content'],
                'pid'=>$info_in['pid']
            ]);
        }
        // update the post attachment if it was updated
        if ($isAttachmentUpdated){
            if ($GLOBALS['debug']) echo '<br>updating post attachment...';
            replace_attachment($info_in,$file_in['attachments']);
        }
        // update the post tags if they were updated
        if ($isTagsUpdated){
            if ($GLOBALS['debug']) echo '<br>updating post tags...';
            replace_post_tags($info_in['tags'],$info_in['pid']);
        }
        // update the post author if the author was updated (admin-only ability)
        if ($isAuthorUpdated){
            if ($GLOBALS['debug']) echo '<br>updating post author...';
            // check if the post has an attachment; if so, move it to new author's dir
            if ($attachment=get_attachments($info_in['pid'])){
                $old_uid=get_post_author($info_in['pid'])['author'];
                change_attachment_user($attachment[0]['file_name'],$old_uid,$info_in['author']);
            }
            // update post author association in db
            db->preparedQuery('UPDATE posts SET author=:uid WHERE pid=:pid',[
                'author'=>$info_in['author']
            ]);
        }
        // return the post pid if all operations successful/if nothing changed
        return $info_in['pid'];
    } catch (Throwable $error) {
        display_system_error('Encountered fatal error when editing post #'.$info_in['pid'],$_SERVER['SCRIPT_NAME']);
        echo '<pre>'.$error.'</pre>';
        return false;
    }
}

// accepts a post ID and deletes it and relevant data from the database
function delete_post($pid){
    if ($GLOBALS['debug']) echo '<br>attempting to delete post #'.$pid.'<br>';
    try {
        // delete tag associations with the post from the DB
        if (get_post_tags($pid)){
            if ($GLOBALS['debug']) echo '<br>post tags detected -- attempting tag deletion<br>';
            delete_post_tag_associations($pid);
        }
        // delete attachment and associations from DB
        if (get_attachments($pid)) {
            if ($GLOBALS['debug']) echo '<br>post attatchment detected -- attempting attachment deletion<br>';
            delete_post_attachment($pid);
        }
        // delete the post from DB
        if ($GLOBALS['debug']) echo '<br>deleting post and post association in database<br>';
        db->preparedQuery('DELETE FROM posts WHERE pid=:pid',[
            'pid'=>$pid
        ]);
        return true;
    } catch (Throwable $error) {
        display_system_error('Encountered fatal error when deleting post #'.$pid,$_SERVER['SCRIPT_NAME']);
        echo '<pre>'.$error.'</pre>';
        return false;
    }
}

// SUB FUNCTIONS --------------------------------------------------------------------------
// read through user db and returns all of users info where the post id matches
function get_post_author($pid){
    $user=db->preparedQuery('SELECT * FROM users INNER JOIN posts ON users.uid=posts.author WHERE pid=:pid;',[
        'pid'=>$pid
    ]);
    if (db->resultFound($user)){
        return $user;
    } else {
        display_system_error('Could not find post with ID #'.$pid.' inside database',$_SERVER['SCRIPT_NAME']);
        return db->query('SELECT * FROM posts WHERE pid=1'); // return example post to avoid php errors
    }
}

// generate pid for new posts and check if they're actually unique -- DEPRECIATED
function generate_pid(){
    $posts=get_all_posts();
    $id_is_unique=false;
    // step through post data file by pids and ensure pid is actually unique. probably not scalable for a million users or something but it works for this
    while(!$id_is_unique){
        $new_pid=rand(100000,999999);

        for ($i=0;$i<count($posts);$i++){
            if ($posts[$i]['pid'] == $new_pid){
                $id_is_unique=false;
                break; // found non-unique id, break out with var set to false and generate another
            }
            $id_is_unique=true; // these ids were unique, set to true so while loop doesn't run again if for loop completes
        }
    }
    return $new_pid;
}

// TAG HANDLING -----------------------------------------------------------------------------
// convert a string of tags into array format. returns the array if successful, false if not
function tags_to_array($tags_in){
    if (isset($tags_in)){
        $tags_in=explode(',',$tags_in);
        $tags_out=[];
        foreach ($tags_in as $tag){
            $tags_out[]=trim($tag);
        }
    } else return false;
    return $tags_out;
}


// parse an array of tags into database for a given post id
function parse_tags_in($tags_in,$pid){
    $tags_out=tags_to_array($tags_in);
    foreach ($tags_out as $tag) {
        // run query to check if tag name exists in database
        // if tag doesn't exist in tags table, run query to create tag and get the new id
        $tid=0;
        $tag_query=db->preparedQuery('SELECT * FROM tags WHERE tag=?',[$tag]);
        if (!db->resultFound($tag_query)){
            db->preparedQuery('INSERT INTO tags (tag) VALUES (:tag)',[
                'tag'=>$tag
            ]);
            // get new tag's id from auto incrementation
            $tid=db->query('SELECT LAST_INSERT_ID()')['LAST_INSERT_ID()'];
        } else { 
            // if tag is found in DB, set tid to found tag's tid for attribution to post
            $tid=$tag_query['tid'];
        }

        // run insert to post_tags if the association doesn't already exist
        if (!db->resultFound(db->preparedQuery('SELECT * FROM post_tags WHERE tid=:tid AND pid=:pid',[
            'tid'=>$tid,
            'pid'=>$pid]))) {
            db->preparedQuery('INSERT INTO post_tags VALUES (:tid,:pid)',[
                'tid'=>$tid,
                'pid'=>$pid
            ]);
        }
    }
}

// reads database and returns array of tags from a post id, if the post has any
function get_post_tags($pid){
    $tags=db->preparedQueryAll('SELECT tag FROM post_tags NATURAL JOIN tags WHERE pid=:pid',[
        'pid'=>$pid
    ]);
    if (db->resultFound($tags)) {
        $tags_out=[];
            foreach ($tags as $tag){
                $tags_out[]=$tag['tag'];
            }
        return $tags_out;
    } else return false;
}

// reads database for a post's tags and prints them out in basic string format if there are any. returns null if there's no tags
function parse_tags_out($pid){
    $tags=get_post_tags($pid);
    $tags_string='';
    if ($tags){
        foreach ($tags as $tag){
            $tags_string.=$tag;
            $tags_string.=($tag !== $tags[count($tags) - 1])?", ":""; // check if tag is last tag, add comma appropriately
        }
    } else {
        return false;
    }
    return $tags_string;
}

// accepts a tag string and a pid, removed posts old tag associations and parses in new ones (or none if none were given)
// does NOT delete unusued tags in db
function replace_post_tags($tags,$pid){
    delete_post_tag_associations($pid);
    if ($tags) parse_tags_in($tags,$pid);
}

// deletes any associations of tags that match a pid
// does NOT delete unused tags in db
function delete_post_tag_associations($pid){
    db->preparedQuery('DELETE FROM post_tags WHERE pid=:pid',[
        'pid'=>$pid
    ]);
}

// COMPARISON FUNCTIONS -----------------------------------------------------------------------
// accepts a raw post info array and compares its text fields with the same post in the DB, returns true if it was modified and false if not
function compare_post_text($post_in){
    $postDB=db->preparedQuery('SELECT * FROM posts WHERE pid=:pid',[
        'pid'=>$post_in['pid']
    ]);
    if ($GLOBALS['debug']) { echo '<br>incoming post text: ';var_dump($post_in); echo 'database post text: '; var_dump($postDB); }

    // go through text fields of incoming post and compare with db post. return false if anything doesn't match
    if ($post_in['title']!==$postDB['title']) return true;
    if ($post_in['content']!==$postDB['content']) return true;
    return false;
}
// compares a raw posts tags with the same post in the db 
function compare_post_tags($post_in){
    // check if the incoming post has any tags assigned. if so, break them into an array. if not, assign tags to false
    $tags_in=(strlen($post_in['tags'])>0)? tags_to_array($post_in['tags']) : false;
    $tagsDB=get_post_tags($post_in['pid']);

    // output debug info
    if ($GLOBALS['debug']){echo '<br>incoming tags: ';var_dump($tags_in); echo 'database tags: '; var_dump($tagsDB);}

    // if no incoming tags and if no tags are found for the post in the db, return false
    if ($tags_in==false && db->resultFound($tagsDB)==false){
        return false;
    }
    // if incoming tags is set but DB tags are not or vice versa, skip comparison and return true
    if (($tags_in==true && db->resultFound($tagsDB)==false)
        ||
        ($tags_in==false && db->resultFound($tagsDB)==true)){
        return true;
    }
    // check if incoming post has the same amount of tags as the db post. if it does, check if each tag is inside the database post's tags. return true if mismatch found, false if all match
    if (count($tags_in)==count($tagsDB)){
        foreach ($tags_in as $tag){
            if (!in_array($tag,$tagsDB)) return true;
        }
    } else return true;
    return false;
}
// compares a raw post and attachment info with the same post and attachment in db (if attachment exists)
function compare_post_attachment($post_in,$attachment_in){
    // find the attachment based on the post id in relationship set and get its array
    $attachmentProvided=is_attachment_provided($attachment_in);
    $attachmentDB=db->preparedQuery(
        'SELECT * FROM attachments WHERE pid=:pid',[
        'pid'=>$post_in['pid']
    ]);
    if ($GLOBALS['debug']){echo '<br>local att: ';var_dump($attachment_in); echo 'database att: '; var_dump($attachmentDB);}

    // if no attachment incoming detected, return false (attachment should only read as changed if one is provided)
    if (($attachmentProvided==false)){
        return false;
    }
    // if only one has an attachment, return true without comparing
    if (($attachmentProvided !== db->resultFound($attachmentDB))){
        return true;
    }
    // if both have attachments, compare their values and return accordingly
    if ($attachmentProvided==true && db->resultFound($attachmentDB)==true){
        if ($attachment_in['name']!==$attachmentDB['file_name']) return true;
        if (strtolower(pathinfo($attachment_in['name'], PATHINFO_EXTENSION))!==$attachmentDB['ext']) return true;
        if ($attachment_in['size']!==$attachmentDB['size']) return true;
        if ($attachment_in['type']!==$attachmentDB['type']) return true;
    }
    return false;
}
// checks if a raw post info still has the same author marked
function compare_post_author($post_in){
    $authorDB=get_post_author($post_in['pid'])['uid'];

    // output debug info
    if ($GLOBALS['debug']){echo '<br>local author: ';var_dump($post_in['author']); echo 'database author: '; var_dump($authorDB);}

    return (intval($post_in['author'])!==$authorDB);
}

// accepts a raw post array and compares its text, tags, attachments, and assigned authors. returns an array with true/false for each if they were changed or not
function compare_post($post_in,$attachment_in){
    // output debug info
    if ($GLOBALS['debug']){
        echo '<br><b>incoming post data to compare: </b>'; var_dump($post_in);
        echo '<br><b>incoming attachment data to compare: </b>'; var_dump($attachment_in);
    }

    $text_changed=compare_post_text($post_in);
    if ($GLOBALS['debug']){ echo '<b>post text changed...</b> ';echo $text_changed?'true<br>':'false<br>'; }

    $tags_changed=compare_post_tags($post_in);
    if ($GLOBALS['debug']){ echo '<b>post tags changed...</b> ';echo $tags_changed?'true<br>':'false<br>'; }

    $attachment_changed=compare_post_attachment($post_in,$attachment_in);
    if ($GLOBALS['debug']){ echo '<b>post attachment changed...</b> ';echo $attachment_changed?'true<br>':'false<br>'; }
    
    $author_changed=compare_post_author($post_in);
    if ($GLOBALS['debug']){ echo '<b>post author changed...</b> ';echo $author_changed?'true<br>':'false<br>'; }
    return [
        'textChanged'=>$text_changed,
        'tagsChanged'=>$tags_changed,
        'attachmentChanged'=>$attachment_changed,
        'authorChanged'=>$author_changed];
}

// ATTACHMENT HANDLING ------------------------------------------------------------------
// gets a file array and returns if it has any data or not
function is_attachment_provided($file_in){
    // check if its already set to false, if not, continue with field check
    return ($file_in && $file_in['error'] != 4);
}

// parse attachment names and info to database and move files to respective user's directories
function parse_attachments($pid,$file_in){
    // check if file coming in has an attachment, do nothing if none detected
    if (!is_attachment_provided($file_in)){
        if($GLOBALS['debug']) {echo '<br>no attachment provided, skipping attachment parse...<br>';}
        return false;
    }
    // if attachment incoming, check if its an allowed filetype. throw exception if not
    else if(in_array(strtolower(pathinfo($file_in['name'], PATHINFO_EXTENSION)),$GLOBALS['attachmentExts'])){
        if($GLOBALS['debug']) {echo '<br>parsing attachment into system...<br>';}

        $ext=strtolower(pathinfo($file_in['name'], PATHINFO_EXTENSION));
        // db insert statement for attachment table and the post relationship set
        db->preparedQuery('INSERT INTO attachments (pid,file_name,ext,size,type,date_created)
            VALUES (:pid,:file_name,:ext,:size,:type,CURRENT_TIMESTAMP)',[
            'pid'=>$pid,
            'file_name'=>$file_in['name'],
            'ext'=>$ext,
            'size'=>$file_in['size'],
            'type'=>$file_in['type']
        ]);
        // move files by temp name to user dir
        move_uploaded_file($file_in['tmp_name'],'../../data/users/'.get_post_author($pid)['author'].'/images/'.$file_in['name']);
    } else {
        throw new Exception('File is not an accepted file type');
    }
}

// accepts a post id and returns an array of its attachments if it has any
function get_attachments($pid){
    $attachments=db->preparedQueryAll('SELECT * FROM attachments WHERE pid=:pid',[
        'pid'=>$pid
    ]);
    return (db->resultFound($attachments))? $attachments : false;
}

//return the attachment of a post
function get_attachment_photo($pid){
    $attachment=db->preparedQuery('SELECT file_name FROM attachments WHERE pid=:pid',['pid'=>$pid]);
    return $attachment['file_name'];
}

// moves a post's attachment to a new user
function change_attachment_user($filename,$user_id_old,$user_id_new){
    // output debug
    if ($GLOBALS['debug']){
        echo 'attempting to move file ../../data/users/'.$user_id_old.'/images/'.$filename;
        echo '<br>to ../../data/users/'.$user_id_new.'/images/'.$filename;
    }
    return rename('../../data/users/'.$user_id_old.'/images/'.$filename,'../../data/users/'.$user_id_new.'/images/'.$filename);
}

// accepts a post and an attachment array, deletes old post attachment files and association and parses a new one
function replace_attachment($post_current,$file_in){
    delete_post_attachment($post_current['pid']);
    return parse_attachments($post_current['pid'],$file_in);
}

// accepts a post id and deletes the attachment file and the db association with the post
function delete_post_attachment($pid){
    $attachments_old=get_attachments($pid);
    // make sure there's an attachment before attempting deletions, otherwise return false
    if ($attachments_old) {
        db->preparedQuery('DELETE FROM attachments WHERE aid=:aid',[
            'aid'=>$attachments_old[0]['aid']
        ]);
        unlink('../../data/users/'.get_post_author($pid)['author'].'/images/'.$attachments_old[0]['file_name']);
        return true;
    } else {
        return false;
    }
}

// PORTFOLIO HANDLING ------------------------------------------------------------------------
// accepts text info and files and creates a porfolio for a user
function get_all_portfolios(){
    return db->queryAll('SELECT * FROM portfolios');
}

function create_portfolio($info, $file){
    if ($GLOBALS['debug']){
        echo '<pre><br>creating portfolio with data:<br>';
        echo '<br>info: ';var_dump($info);
        echo '<br>file: ';var_dump($file);
        echo '</pre>';
    }
    try {
        // take uploaded image(s) and move to users folder & concat names to name array
        $filenames='';
        for($i=0;$i<count($file['images']['name']);$i++){
            if(in_array(strtolower(pathinfo($file['images']['name'][0], PATHINFO_EXTENSION)),get_file_extensions())){
                if ($GLOBALS['debug']) echo '<br>parsing in file '.$file['images']['full_path'][$i].' i='.$i.'</br>';

                move_uploaded_file($file['images']['tmp_name'][$i],'../../data/users/'.$info['user_id'].'/images/'.$file['images']['full_path'][$i]);

                // add filename(s) to name array and add comma if its not the last in the list
                $filenames.=$file['images']['full_path'][$i];
                $filenames.=($i!=count($file['images']['full_path'])-1) ? ',' : '';
            }
        }
        // get portfolio id and push portfolio info to database
        db->preparedQuery('INSERT INTO portfolios (author,name,category,images)
            VALUES (:author,:name,:category,:images)',[
            'author' => $info['uid'],
            'name' => $info['name'],
            'category' => $info['category'],
            'images' => $filenames
        ]);
        return true;
    } catch (Throwable $error) {
        display_system_error('Encountered fatal error when attempting to create portfolio',$_SERVER['SCRIPT_NAME']);
        echo '<pre>'.$error.'</pre>';
        return false;
    }
}

//return portfolios of user from their uid if they have any
function get_user_portfolio($uid){
    $portfolio=db->preparedQueryAll('SELECT * FROM portfolios WHERE author=:uid',[
        'uid'=>$uid
    ]);
    if (db->resultFound($portfolio)){
        // convert images string into array before returning portfolio object
        for($i=0;$i<count($portfolio);$i++){
            $portfolio[$i]['images']=explode(',',$portfolio[$i]['images']);
        }
        return $portfolio;
    } else {
        return false;
    }
}