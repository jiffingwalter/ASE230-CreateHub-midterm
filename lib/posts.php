<?php
require_once('general.php');
require_once('users.php');
require_once('../../scripts/readJSON.php');

//get list of all user posts
function get_all_posts(){
    $users=get_all_users();
    $post_list_all=[];

    // go through all users, read their posts and append to list
    foreach($users as $user){
        $post_list_all=array_merge($post_list_all,get_user_posts($user['id']));
    }
    
    return $post_list_all;
}

// return all posts by a specific user by ID
function get_user_posts($user_id){
    $user_posts=readJSON('../../data/users/'.$user_id.'/posts.json');
    $post_list=[];

    // go through user posts, read their posts and append to list
    foreach($user_posts as $post){
        $post_list+=$post;
    }

    return readJSON('../../data/users/'.$user_id.'/posts.json');
}

//return portfolio of user
function get_user_portfolio($user_id){
    return readJSON('../../data/users/'.$user_id.'/portfolio.json');
}

// return single post by searching by pid in post file
function get_post($pid){
    $posts=get_all_posts();
    $pid_found=false;

    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['pid'] == $pid){
            $pid_found=true;
            break;
        }
    }

    if ($pid_found){
        return $posts[$i];
    } else {
        display_system_error('Could not find post pid #'.$pid.' inside post data file',$_SERVER['SCRIPT_NAME']);
        return $posts[0]; // return example post to attempt to avoid php errors
    }
}

function create_post($info_in,$file_in){
    $posts_updated=get_user_posts($info_in['author']);

    // lay out new post text
    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['author'],
        'content' => $info_in['content'],
        'attachments' => parse_attachments($info_in,$file_in),
        'tags' => parse_tags_in($info_in['tags']),
        'date_created' => get_timestamp(),
        'last_edited' => get_timestamp(),
        'pid' => generate_pid(),
    ];

    // update post data files
    $posts_updated[count($posts_updated)]=$new_post; // append new post to the end of file
    file_put_contents('../../data/users/'.$info_in['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the json data
    return $new_post['pid'];
}

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
    $selected_post['tags']=(isset($info_in['tags'][0]) && strlen($info_in['tags'][0])>0)?parse_tags_in($info_in['tags']):[""]; // sloppy way of avoiding php errors if tags arent set in different cases
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
function generate_pid(){
    $posts=get_all_posts();
    $id_is_unique=false;
    // step through post data file by pids and ensure pid is actually unique. probably not scalable for a million users or something but it works for this
    while(!$id_is_unique){
        $new_pid='p'.rand(100000,999999);

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

// turn tags into array -- TODO: make it so blank tags get discarded
function parse_tags_in($tags_in){
    $tags_out=explode(',',$tags_in);
    for($i=0;$i < count($tags_out);$i++){
        $tags_out[$i]=trim($tags_out[$i]);
    }
    return $tags_out;
}

// print array of tags
function parse_tags_out($tags_in){
    $tags_out='';
    foreach ($tags_in as $tag){
        echo $tag;
        echo ($tag !== $tags_in[count($tags_in) - 1])?", ":""; // check if tag is last tag, add comma appropriately
    } 
    return $tags_out;
}

// read through user list and return an author's email from their id
function get_post_author($user_id){
    $users=get_all_users();
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($users);$i++){
        if ($users[$i]['id'] == $user_id){
            $id_found=true;
            break;
        }
    }

    // return user id if found. return empty user and throw error if user not found
    if ($id_found){
        return $users[$i]['email'];
    } else {
        display_system_error('Could not find user with ID #'.$user_id.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return $users[0];
    }
}

// attachment stuff ------------------------------------------------------------------
// parse attachments to readable format and return attachment array - TODO -- if support for more attachments on one post is added, need to refactor
function parse_attachments($post_info,$file_in){
    $new_attachment=[];
    if(isset($file_in['attachments']) && $file_in['attachments']['error'] != 4
        &&
        in_array(strtolower(pathinfo($file_in['attachments']['name'], PATHINFO_EXTENSION)),get_file_extensions())){
        $file = $file_in['attachments'];
        $file[count($file)] = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_attachment = $file;
        move_uploaded_file($file['tmp_name'],'../../data/users/'.$post_info['author'].'/images/'.$file['full_path']);
    }else{
        $new_attachment = ['error' => 'noFileUploaded'];
    }
    return $new_attachment;
}

// accepts a post id and returns a list of its attachment filenames -- TODO: if we add support for multiple images in one post, add support for that
function get_post_attachments($post_id){
    $selected_post=get_post($post_id);
    return [$selected_post['attachments']['name']];
}

// moves a post's attachment to a new user
function change_attachment_user($filename,$user_id_old,$user_id_new){
    echo 'attempting to move file ../../data/users/'.$user_id_old.'/images/'.$filename;
        echo '<br>to ../../data/users/'.$user_id_new.'/images/'.$filename;
        return rename('../../data/users/'.$user_id_old.'/images/'.$filename,'../../data/users/'.$user_id_new.'/images/'.$filename);
}

// accepts a post and an attachment array, returns new attachment array and deletes old attachment files
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