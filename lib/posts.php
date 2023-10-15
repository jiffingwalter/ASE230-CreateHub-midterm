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
    //return readJSON('../../data/users/user_posts.json');
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

// return single post by searching by UID in post file
function get_post($uid){
    $posts=get_all_posts();
    $uid_found=false;

    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['uid'] == $uid){
            $uid_found=true;
            break;
        }
    }

    if ($uid_found){
        return $posts[$i];
    } else {
        display_system_error('Could not find post UID #'.$uid.' inside post data file',$_SERVER['SCRIPT_NAME']);
        return $posts[0]; // return example post to attempt to avoid php errors
    }
}

function create_post($info_in,$file_in){
    $posts_updated=get_user_posts($info_in['user_id']);

    // lay out new post text
    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['user_id'],
        'content' => $info_in['content'],
        'attachments' => '',
        'tags' => parse_tags_in($info_in['tags']),
        'date_created' => get_timestamp(),
        'last_edited' => get_timestamp(),
        'uid' => generate_uid(),
    ];

    // handle image if one is added
    if(isset($file_in['image']) && $file_in['image']['error'] != 4
        &&
        in_array(strtolower(pathinfo($file_in['image']['name'], PATHINFO_EXTENSION)),get_file_extensions())){
        $img = $file_in['image'];
        $img[count($img)] = pathinfo($img['name'], PATHINFO_EXTENSION);
        $new_post['attachments'] = $img;
        move_uploaded_file($img['tmp_name'],'../users/'.$info_in['user_id'].'/images/'.$img['full_path']);
    }else{
        $new_post['attachments'] = ['error' => 'noFileUploaded'];
    }

    $posts_updated[count($posts_updated)]=$new_post; // append new post to the end of file
    file_put_contents('../../data/users/'.$info_in['user_id'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the json data

    display_message('Created new post #'.$new_post['uid'].'!','lightskyblue');
    header('Location: index.php'); // redirect to index
}

function edit_post($info_in){
    // get post list
    $posts=get_user_posts($info_in);

    // find index that matches uid
    $index=0;
    $uid_found=false;
    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['uid'] == $info_in['uid']){
            $uid_found=true;
            $index=$i; // get index for modification
            break;
        }
    }

    // handle edit if uid is found, throw error if not
    if ($uid_found){
        $posts[$index]['title']=$info_in['title'];
        $posts[$index]['author']=$info_in['author'];
        $posts[$index]['content']=$info_in['content'];
        $posts[$index]['tags']=parse_tags_in($info_in['tags']);
        $posts[$index]['last_edited']=get_timestamp();

        // update data file with new results
        file_put_contents('../../data/users/user_posts.json',json_encode($posts,JSON_PRETTY_PRINT));
        header('Location: index.php'); // redirect to index
    } else {
        display_system_error('Could not find post UID #'.$info_in['uid'].' inside post data file',$_SERVER['SCRIPT_NAME']);
    }
}

function delete_post($info_in){
    // get post lists
    $posts=get_all_posts();

    // find index that matches uid
    $index=0;
    $uid_found=false;
    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['uid'] == $info_in['uid']){
            $uid_found=true;
            $index=$i; // get index for modification
            break;
        }
    }

    // splice post from temp data file if uid was found and update real data file, throw an error if not
    if ($uid_found){
        array_splice($posts,$index,$index+1);
        file_put_contents('../../data/users/user_posts.json',json_encode($posts,JSON_PRETTY_PRINT));
        header('Location: index.php'); // redirect to index
    } else {
        display_system_error('Could not find post UID #'.$info_in['uid'].' inside post data file',$_SERVER['SCRIPT_NAME']);
    }
}

// sub functions ------------------- might be able to move these into lib?
// generate uid for new posts and check if they're actually unique
function generate_uid(){
    $posts=get_all_posts();
    $id_is_unique=false;
    // step through post data file by UIDs and ensure uid is actually unique. probably not scalable for a million users or something but it works for this
    while(!$id_is_unique){
        $new_uid='p'.rand(100000,999999);

        for ($i=0;$i<count($posts);$i++){
            if ($posts[$i]['uid'] == $new_uid){
                $id_is_unique=false;
                break; // found non-unique id, break out with var set to false and generate another
            }
            $id_is_unique=true; // these ids were unique, set to true so while loop doesn't run again if for loop completes
        }
    }
    return $new_uid;
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