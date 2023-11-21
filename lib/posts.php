<?php
require_once('general.php');
require_once('users.php');
require_once($GLOBALS['readJSONDirectory']);

//get list of all user posts
//  -- DONE
function get_all_posts(){
    return db->queryAll('SELECT * FROM posts');
}

// return all posts by a specific user by ID
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
//  -- IN PROGRESS
function create_post($info_in,$file_in){
    try {
        // initialization
        $post_id=generate_pid();
        $attachment=(is_attachment_provided($file_in));
        $tags=(count($info_in['tags'])>0);
        // push post info to database
        db->preparedQuery('INSERT INTO posts VALUES (:pid,:title,:content,:has_attachment,:date_created,:last_edited)',[
            'pid'=>$post_id,
            'title'=>$info_in['title'],
            'content'=>$info_in['content'],
            'has_attachment'=>($attachment),
            'date_created' => get_timestamp(),
            'last_edited' => get_timestamp()
        ]);
        // push user and post relationship to database
        db->preparedQuery('INSERT INTO user_posts VALUES (:user_id,:post_id)',[
            'user_id'=>$info_in['author'],
            'post_id'=>$post_id
        ]);
        // handle attachment and push infos to database if one is provided
        if ($attachment) {
            parse_attachments($info_in,$file_in['attachments']);
        }
        // handle tags and push infos to database if any are provided
        if ($tags) {
            parse_tags_in($info_in['tags'],$info_in['pid']);
        }
        // return post pid if successful
        return $post_id;
    } catch (Exception $ex) {
        return false;
    }

    //OLD----------------------------
    // $posts_updated=get_user_posts($info_in['author']);

    // // lay out new post text
    // $new_post=[
    //     'title' => $info_in['title'],
    //     'author' => $info_in['author'],
    //     'content' => $info_in['content'],
    //     'attachments' => parse_attachments($info_in,$file_in),
    //     'tags' => parse_tags_in($info_in['tags']),
    //     'date_created' => get_timestamp(),
    //     'last_edited' => get_timestamp(),
    //     'pid' => generate_pid(),
    // ];

    // // update post data files
    // $posts_updated[count($posts_updated)]=$new_post; // append new post to the end of file
    // file_put_contents('../../data/users/'.$info_in['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the json data
    // return $new_post['pid'];
}

// accepts a post and attachment info and edits an existing post, returns pid on success, false if not
//  -- OUT OF DATE
function edit_post($info_in,$file_in){ 
    // pull requested post by pid to get all its info
    $selected_post=get_post($info_in['pid']);

    // TODO -- make sure changing author and attachments at same time doesn't blow everything up
    // move attachment IF... there is an attachment, the author was changed, and if the attachment itself wasn't being changed
    if ($selected_post['attachments']['error'] != 'noFileUploaded' && $info_in['author']!=$selected_post['author'] && !isset($file_in)){
        change_attachment_user($selected_post['attachments']['name'],$selected_post['author'],$info_in['author']);
    }

    // update attachment if a one is provided
    if (isset($file_in['attachments']) && $file_in['attachments']['error']!=4){
        $selected_post['attachments']=replace_attachment($selected_post,$file_in);
    }

    // update/reinsert the post text info that was returned
    $selected_post['title']=$info_in['title'];
    $selected_post['author']=$info_in['author'];
    $selected_post['content']=$info_in['content'];
    $selected_post['tags']=(isset($info_in['tags'][0]) && strlen($info_in['tags'][0])>0)?parse_tags_in($info_in['tags'],$info_in['pid']):[""]; // sloppy way of avoiding php errors if tags arent set in different cases
    $selected_post['last_edited']=get_timestamp();

    // delete original post
    delete_post($info_in['pid'],false);

    // get users post list add post to given user's post data file
    $posts_updated=get_user_posts($selected_post['author']);
    $posts_updated[]=$selected_post; // append edited post to the end of file
    file_put_contents('../../data/users/'.$selected_post['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the users post json data

    return true;
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

// sub functions
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

// parse tags into database for a post
//  -- DONE
function parse_tags_in($tags_in,$pid){
    $tags_out=explode(',',$tags_in);
    for($i=0;$i < count($tags_out);$i++){
        $tags_out[$i]=trim($tags_out[$i]);
    }
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
            // if tag is found in DB, set tid to its tid for attribution to post
            $tid=$tag_query['tid'];
        }
        // run insert to post_tags if the association doesn't already exist
        if (!db->resultFound(db->preparedQuery('SELECT * FROM post_tags WHERE tid=:tid AND pid=:pid',[
            'tid'=>$tid,
            'pid'=>$pid
        ]))) {
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
    return (db->resultFound($tags))? $tags : false;
}

// reads database for a posts tags and prints them out in basic format if there are any. returns false if there's no tags found
//  -- DONE
function parse_tags_out($pid){
    $tags=get_post_tags($pid);
    if ($tags){
        foreach ($tags as $tag){
            echo $tag['tag'];
            echo ($tag !== $tags[count($tags) - 1])?", ":""; // check if tag is last tag, add comma appropriately
        }
    } else {
        return false;
    }
}

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

// attachment stuff ------------------------------------------------------------------
// gets attachment info and verifies if its empty or not
function is_attachment_provided($file_in){
    return (isset($file_in['attachments']) && $file_in['attachments']['error'] != 4);
}

// parse attachment names and info to database and move files to respective user's directories
//  -- IN PROGRESS
function parse_attachments($post_info,$file_in){
    if(is_attachment_provided($file_in) && in_array(strtolower(pathinfo($file_in['attachments']['name'], PATHINFO_EXTENSION)),$GLOBALS['attachmentExts'])){
        echo '<pre>'; var_dump($file_in);
        // db insert statement for attachment table and the post relationship set
        // move files by temp name to user dir
    }

    die;// OLD -----------------------
    $new_attachment=[];
    if(isset($file_in['attachments']) && $file_in['attachments']['error'] != 4
        && in_array(strtolower(pathinfo($file_in['attachments']['name'], PATHINFO_EXTENSION)),$GLOBALS['attachmentExts'])){
        $file = $file_in['attachments'];
        $file[count($file)] = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_attachment = $file;
        move_uploaded_file($file['tmp_name'],'../../data/users/'.$post_info['author'].'/images/'.$file['full_path']);
    }else{
        $new_attachment = ['error' => 'noFileUploaded'];
    }
    return $new_attachment;
}

// accepts a post id and returns an array of its attachments if it has any
//  -- DONE
function get_post_attachments($pid){
    $attachments=db->preparedQueryAll('SELECT * FROM attachments NATURAL JOIN attached_to WHERE pid=:pid',[
        'pid'=>$pid
    ]);
    return (db->resultFound($attachments))? $attachments : false;
}

// moves a post's attachment to a new user
//  -- OUT OF DATE
function change_attachment_user($filename,$user_id_old,$user_id_new){
    echo 'attempting to move file ../../data/users/'.$user_id_old.'/images/'.$filename;
        echo '<br>to ../../data/users/'.$user_id_new.'/images/'.$filename;
        return rename('../../data/users/'.$user_id_old.'/images/'.$filename,'../../data/users/'.$user_id_new.'/images/'.$filename);
}

// accepts a post and an attachment array, returns new attachment array and deletes old attachment files
//  -- OUT OF DATE
function replace_attachment($post_current,$file_in){
    // parse new attachment 
    $attachments_new=parse_attachments($post_current,$file_in);
    // if there was an attachment previously, delete it
    if ($post_current['attachments']['error']!="noFileUploaded"){
        unlink('../../data/users/'.$post_current['author'].'/images/'.$post_current['attachments']['name']);
    }
    return $attachments_new;
}

// reset an attachment back to empty, returns nothing if succeeded and false if it didnt detect any attachments
//  -- OUT OF DATE
function delete_attachment($post_id){
    $selected_post=get_post($post_id);
    if ($selected_post['attachments']['error']!="noFileUploaded"){
        // delete attachment and original post, recreate original post but with no attachment
        $selected_post['attachments']['error']="noFileUploaded";
        delete_post($post_id,true);
        $posts_updated=get_user_posts($selected_post['author']);
        $posts_updated[]=$selected_post; // append edited post to the end of file
        file_put_contents('../../data/users/'.$selected_post['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the users post json data
    } else {
        return false;
    }
}

// portfolio stuff -------------------------------------------------
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