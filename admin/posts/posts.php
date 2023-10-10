<?php

//get user posts on initilization
function get_posts(){
    $posts='../../data/users/user_posts.json';
    if (file_exists($posts)){
        $json_file=file_get_contents($posts);
        return json_decode($json_file,true);
    } else {
        echo 'ERROR - Post data not found at given location: '.$posts.'<BR>';
    }
}

function generate_uid(){
    $posts=get_posts();
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

function create_post($info_in){
    $timestamp=date("m-d-y:h:i:sa"); // gets current date/time - 
    $entries_updated=get_products(); // set enteries_updated to the json data as php array
    $new_post=[
        'title' => $info_in['title'],
        'author' => $info_in['author'],
        'content' => $info_in['content'],
        'tags' => $info_in['tags'],
        'date_created' => $timestamp,
        'last_edited' => $timestamp,
        'uid' => generate_uid(), // generates unique id
    ];
    $entries_updated[count($entries_updated)]=$new_post; // add the new entry to the json data
    $updated = json_encode($entries_updated,JSON_PRETTY_PRINT); // set updated to the json data as json
    echo '<pre>';
    print_r($updated);
    file_put_contents('../../data/products.json',$updated); // update the json data
    header('Location: index.php');
}

function edit_post($info_in){

}

function delete_post($info_in){

}