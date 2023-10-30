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
    $posts_updated=get_user_posts($info_in['author']);

    // lay out new post text
    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['author'],
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
        move_uploaded_file($img['tmp_name'],'../../data/users/'.$info_in['author'].'/images/'.$img['full_path']);
    }else{
        $new_post['attachments'] = ['error' => 'noFileUploaded'];
    }

    $posts_updated[count($posts_updated)]=$new_post; // append new post to the end of file
    file_put_contents('../../data/users/'.$info_in['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the json data

    display_message('Created new post #'.$new_post['uid'].'!');
    header('Location: index.php'); // redirect to index
}

function edit_post($info_in){
    // pull requested post by uid to get all its info and remove it from file
    $selected_post=get_post($info_in['uid']);
    $author_original=$selected_post['author'];

    // update the post text info that was returned
    $selected_post['title']=$info_in['title'];
    $selected_post['author']=$info_in['author'];
    $selected_post['content']=$info_in['content'];
    $selected_post['tags']=parse_tags_in($info_in['tags']);
    $selected_post['last_edited']=get_timestamp();

    // TODO -- add logic here to update attachment stuff

    // get users post list add post to given user's post data file
    $posts_updated=get_user_posts($selected_post['author']);
    $posts_updated=$selected_post; // append edited post to the end of file
    file_put_contents('../../data/users/'.$selected_post['author'].'/posts.json',json_encode($posts_updated,JSON_PRETTY_PRINT)); // update the users post json data
    // move attachment if one exists and the author was changed
    if (isset($selected_post['attachments']['name']) && $selected_post['author']!=$author_original){
        rename('../../data/users/'.$author_original['author'].'/images/'.$selected_post['attachments']['name'],
                '../../data/users/'.$selected_post['author'].'/images/'.$selected_post['attachments']['name']);
    }

    // delete original post and give updated message
    //delete_post($info_in['uid']);
    display_message('Updated post #'.$selected_post['uid'].'!');
    header('Location: index.php'); // redirect to index
}

// accepts a post ID and deletes it from it's user's post data
function delete_post($post_uid){
    // NEEDS UPDATING FOR NEW SYSTEM, DO NOT USE
    // changes to be made: need to find post in the current user's directory posts file and delete from there
    // get the post info for the post to be deleted, then the authors's post list
    $selected_post=get_post($post_uid);
    $posts=get_user_posts($selected_post['author']);

    // find index in user posts that matches post uid for deletion
    $index=0;
    for ($i=0;$i<count($posts);$i++){
        if ($posts[$i]['uid'] == $post_uid){
            $index=$i; // get index for modification
            break;
        }
    }

    // splice post if uid was found and update real data file
    array_splice($posts,$index,$index+1);
    file_put_contents('../../data/users/'.$selected_post['author'].'/posts.json',json_encode($posts,JSON_PRETTY_PRINT));
    // delete post image if one is attached
    if (isset($selected_post['attachments']['name'])){
        unlink('../../data/users/'.$selected_post['author'].'/images/'.$selected_post['attachments']['name']);
    }
    header('Location: index.php'); // redirect to index
}

// sub functions
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

// accepts a post id and returns a list of its attachment filenames -- TODO -- if we add support for multiple images in one post, add support for that
function get_post_attachments($post_id){
    $selected_post=get_post($post_id);
    return [$selected_post['attachments']['name']];
}

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