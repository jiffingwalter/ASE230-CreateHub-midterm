<?php
require_once('general.php');
require_once('users.php');
require_once($GLOBALS['readJSONDirectory']);

//get list of all user posts
//  -- DONE
function get_all_posts(){
    return db->queryAll('SELECT * FROM posts');
}

// return all posts by a specific user by ID -- todo: add order parameter to modify order of returned query
//  -- DONE
function get_user_posts($user_id){
    return db->preparedQueryAll('SELECT * FROM posts NATURAL JOIN user_posts WHERE uid=:uid',[
        'uid'=>$user_id
    ]);
}

// return single post by searching by pid in post file
//  -- DONE
function get_post($pid){
    $post=db->preparedQuery('SELECT * FROM posts WHERE pid=?',[$pid]);
    if (db->resultFound($post)){
        return $post;
    } else {
        display_system_error('Could not find post with ID #'.$pid.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return db->query('SELECT * FROM posts WHERE pid=0'); // return example post to avoid php errors
    }
}

// creates a new post, and attachments/tags if added. returns true if made successfully and false if not
//  -- DONE
function create_post($info_in,$file_in){
    try {
        // initialization
        $post_id=generate_pid();
        $isAttachmentProvided=(is_attachment_provided($file_in['attachments']));
        $isTagsProvided=(strlen($info_in['tags'])>0);

        // debug info
        if ($GLOBALS['debug']){
            echo 'incoming data...<br>';var_dump($info_in);var_dump($file_in);
        }

        // push post info to database
        db->preparedQuery('INSERT INTO posts VALUES (:pid,:title,:content,:has_attachment,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)',[
            'pid'=>$post_id,
            'title'=>$info_in['title'],
            'content'=>$info_in['content'],
            'has_attachment'=>($isAttachmentProvided)
        ]);
        // push user and post relationship to database
        db->preparedQuery('INSERT INTO user_posts VALUES (:user_id,:post_id)',[
            'user_id'=>$info_in['author'],
            'post_id'=>$post_id
        ]);

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
//  -- IN PROGRESS
function edit_post($info_in,$file_in){ 
    try{
        // debug info
        if ($GLOBALS['debug']){
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
                $old_uid=get_post_author($info_in['pid'])['uid'];
                change_attachment_user($attachment[0]['file_name'],$old_uid,$info_in['author']);
            }
            // update post author association in db
            db->preparedQuery('UPDATE user_posts SET uid=:uid WHERE pid=:pid',[
                'uid'=>$info_in['author'],
                'pid'=>$info_in['pid']
            ]);
        }
        // return the post pid if all operations successful/if nothing changed
        return $info_in['pid'];
    } catch (Throwable $error) {
        display_system_error('Encountered fatal error when editing post #'.get_post_author($info_in['pid'])['uid'],$_SERVER['SCRIPT_NAME']);
        echo '<pre>'.$error.'</pre>';
        return false;
    }
}

// accepts a post ID and deletes it from it's user's post data
//  -- OUT OF DATE
function delete_post($post_pid,$delete_attachment){
    // get the post info for the post to be deleted, then the authors's post list
    $selected_post=get_post($post_pid);
    $posts=get_user_posts($selected_post['author']);

    // find index in user posts that matches post pid for deletion
    $index=0;
    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['pid'] == $post_pid){
            $index=$i; // get index for modification
            break;
        }
    }

    // splice post if pid was found and update real data file
    array_splice($posts,$index,$index+1);
    file_put_contents('../../data/users/'.$selected_post['author'].'/posts.json',json_encode($posts,JSON_PRETTY_PRINT));
    // delete post attachment if var is true and there's an attachment
    if ($selected_post['attachments']['error'] != 'noFileUploaded' && $delete_attachment){
        $attachment_dir='../../data/users/'.$selected_post['author'].'/images/'.$selected_post['attachments']['name'];
        file_exists($attachment_dir)?unlink($attachment_dir):display_error('Warning: File not found at '.$attachment_dir.' (probably already deleted)');
    }
    return true;
}

// SUB FUNCTIONS --------------------------------------------------------------------------
// read through user list and returns a users info where the post id matches
//  -- DONE
function get_post_author($pid){
    $user=db->preparedQuery('SELECT * FROM users NATURAL JOIN user_posts WHERE pid=:pid',[
        'pid'=>$pid
    ]);
    if (db->resultFound($user)){
        return $user;
    } else {
        display_system_error('Could not find post with ID #'.$pid.' inside database',$_SERVER['SCRIPT_NAME']);
        return db->query('SELECT * FROM posts WHERE pid=0'); // return example post to avoid php errors
    }
}

// generate pid for new posts and check if they're actually unique
//  -- DONE??
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
//  -- DONE
function parse_tags_in($tags_in,$pid){
    $tags_out=tags_to_array($tags_in);
    foreach ($tags_out as $tag) {
        // run query to check if tag name exists in database
        // if tag doesn't exist in tags table, run query to create tag and its id
        $tid=0;
        $tag_query=db->preparedQuery('SELECT * FROM tags WHERE tag=?',[$tag]);
        if (!db->resultFound($tag_query)){
            $tid=count(db->queryAll('SELECT tid FROM tags'))+1; // TEMP, will auto increment id in DB later
            db->preparedQuery('INSERT INTO tags VALUES (:tid,:tag)',[
                'tid'=>$tid,
                'tag'=>$tag
            ]);
        } else { 
            // if tag is found in DB, set tid to found tags tid for attribution to post
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
//  -- DONE
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
//  -- DONE
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
        'SELECT *
        FROM attachments NATURAL JOIN attached_to
        WHERE pid=:pid',[
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
//  -- DONE
function parse_attachments($pid,$file_in){
    // check if file coming in has an attachment, do nothing if so
    if (!is_attachment_provided($file_in)){
        if($GLOBALS['debug']) {echo '<br>no attachment provided, skipping attachment parse...<br>';}
        return false;
    }
    // if attachment incoming, check if its an allowed filetype. throw exception if not
    else if(in_array(strtolower(pathinfo($file_in['name'], PATHINFO_EXTENSION)),$GLOBALS['attachmentExts'])){
        if($GLOBALS['debug']) {echo '<br>parsing attachment into system...<br>';}

        $aid=count(db->queryAll('SELECT aid FROM attachments'))+1;
        $ext=strtolower(pathinfo($file_in['name'], PATHINFO_EXTENSION));
        // db insert statement for attachment table and the post relationship set
        db->preparedQuery('INSERT INTO attachments VALUES (:aid,:file_name,:ext,:size,:type,CURRENT_TIMESTAMP)',[
            'aid'=>$aid,
            'file_name'=>$file_in['name'],
            'ext'=>$ext,
            'size'=>$file_in['size'],
            'type'=>$file_in['type']
        ]);
        db->preparedQuery('INSERT INTO attached_to VALUES (:aid,:pid)',[
            'aid'=>$aid,
            'pid'=>$pid
        ]);
        // move files by temp name to user dir
        move_uploaded_file($file_in['tmp_name'],'../../data/users/'.get_post_author($pid)['uid'].'/images/'.$file_in['name']);
    } else {
        throw new Exception('File is not an accepted file type');
    }
}

// accepts a post id and returns an array of its attachments if it has any
//  -- DONE
function get_attachments($pid){
    $attachments=db->preparedQueryAll('SELECT * FROM attachments NATURAL JOIN attached_to WHERE pid=:pid',[
        'pid'=>$pid
    ]);
    return (db->resultFound($attachments))? $attachments : false;
}

// moves a post's attachment to a new user
//  -- DONE
function change_attachment_user($filename,$user_id_old,$user_id_new){
    // output debug
    if ($GLOBALS['debug']){
        echo 'attempting to move file ../../data/users/'.$user_id_old.'/images/'.$filename;
        echo '<br>to ../../data/users/'.$user_id_new.'/images/'.$filename;
    }
    return rename('../../data/users/'.$user_id_old.'/images/'.$filename,'../../data/users/'.$user_id_new.'/images/'.$filename);
}

// accepts a post and an attachment array, deletes old post attachment files and association and parses a new one
//  -- DONE
function replace_attachment($post_current,$file_in){
    delete_post_attachment($post_current['pid']);
    return parse_attachments($post_current['pid'],$file_in);
}

// accepts a post id and deletes the attachment file and the db association with the post
//  -- DONE
function delete_post_attachment($pid){
    $attachments_old=get_attachments($pid);
    db->preparedQuery('DELETE FROM attached_to WHERE aid=:aid AND pid=:pid',[
        'aid'=>$attachments_old[0]['aid'],
        'pid'=>$pid
    ]);
    db->preparedQuery('DELETE FROM attachments WHERE aid=:aid',[
        'aid'=>$attachments_old[0]['aid']
    ]);
    unlink('../../data/users/'.get_post_author($pid)['uid'].'/images/'.$attachments_old[0]['file_name']);
}

// PORTFOLIO HANDLING ------------------------------------------------------------------------
// accepts text info and files and creates a porfolio for a user
//  -- OUT OF DATE
function create_portfolio($info, $file){
    for($i=0;$i<count($file['images']['name']);$i++){
        if(in_array(strtolower(pathinfo($file['images']['name'][0], PATHINFO_EXTENSION)),get_file_extensions())){
            //put image in images folder
            move_uploaded_file($file['images']['tmp_name'][$i],'../../data/users/'.$info['user_id'].'/images/'.$file['images']['full_path'][$i]);
        }
    }
    //add info to portfolio.json
    $portfolio_updated=get_user_portfolio($info['user_id']);
    $new_portfolio=[
        'name' => $info['name'],
        'category' => $info['category'],
        'images' => $file['images']['name']
    ];
    $portfolio_updated[count($portfolio_updated)]=$new_portfolio;
    file_put_contents('../../data/users/'.$info['user_id'].'/portfolio.json', json_encode($portfolio_updated,JSON_PRETTY_PRINT));
    header('Location: index.php');
}

//return portfolios of user from their uid if they have any
//  -- DONE
function get_user_portfolio($uid){
    $portfolio=db->preparedQueryAll('SELECT * FROM portfolios NATURAL JOIN user_portfolios WHERE uid=:uid',[
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