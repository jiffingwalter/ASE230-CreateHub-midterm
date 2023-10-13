<?php
require_once('../../lib/general.php');

//get list of all user posts
function get_all_posts(){
    $posts='../../data/users/user_posts.json';
    if (file_exists($posts)){
        $json_file=file_get_contents($posts);
        return json_decode($json_file,true);
    } else {
        display_error('Post data not found at given location: '.$posts,$_SERVER['SCRIPT_NAME']);
    }
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
        display_error('Could not find post UID #'.$uid.' inside post data file',$_SERVER['SCRIPT_NAME']);
        return $posts[0]; // return example post to attempt to avoid php errors
    }

}

function create_post($info_in){
    $posts_updated=get_all_posts();

    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['author'],
        'content' => $info_in['content'],
        'tags' => parse_tags_in($info_in['tags']),
        'date_created' => get_timestamp(),
        'last_edited' => get_timestamp(),
        'uid' => generate_uid(), // generates unique id
    ];

    $posts_updated[count($posts_updated)]=$new_post; // append new post to the end of file
    file_put_contents('../../data/users/user_posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the json data

    display_message('Created new post #'.$new_post['uid'].'!');
    header('Location: index.php'); // redirect to index
}

function edit_post($info_in){
    // get post list
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
        display_error('Could not find post UID #'.$info_in['uid'].' inside post data file',$_SERVER['SCRIPT_NAME']);
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
        display_error('Could not find post UID #'.$info_in['uid'].' inside post data file',$_SERVER['SCRIPT_NAME']);
    }
}

// sub functions ------------------- might be able to move these into lib?
// generate uid for new posts and check if they're actually unique
function generate_uid(){
    $posts=get_all_posts();
    $id_is_unique=false;
    // step through post data file by UIDs and ensure uid is actually unique. probably not scalable for a million users or something but it works for this
    while(!$id_is_unique){
        $new_uid=rand(10000,99999);

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

function get_timestamp(){
    return date("m-d-y h:i:sa");
}