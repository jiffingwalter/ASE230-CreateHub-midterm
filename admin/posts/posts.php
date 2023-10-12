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
    $timestamp=date("m-d-y:h:i:sa"); // gets current date/time - 
    $posts_updated=get_all_posts(); // set enteries_updated to the json data as php array
    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['author'],
        'content' => $info_in['content'],
        'tags' => explode(',',$info_in['tags']),
        'date_created' => $timestamp,
        'last_edited' => $timestamp,
        'uid' => generate_uid(), // generates unique id
    ];
    $posts_updated[count($posts_updated)]=$new_post; // add the new entry to the json data
    $updated = json_encode($posts_updated,JSON_PRETTY_PRINT); // set updated to the json data as json
    echo '<pre>';
    print_r($updated);
    file_put_contents('../../data/products.json',$updated); // update the json data
    header('Location: index.php'); // redirect to index
}

function edit_post($info_in){

}

function delete_post($info_in){

}

// sub functions -------------------
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

// print array of tags
function parse_tags_out($tags_in){
    $tags_string='';
    foreach ($tags_in as $tag){
        echo $tag;
        echo ($tag !== $tags_in[count($tags_in) - 1])?", ":""; // check if tag is last tag, add comma appropriately
    } 
    return $tags_string;
}