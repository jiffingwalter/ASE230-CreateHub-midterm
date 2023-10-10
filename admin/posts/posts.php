<?php

//get user posts on initilization
function get_posts(){
    $posts='../../data/users/user_posts.json';
    if (file_exists($posts)){
        $json_file=file_get_contents($posts);
        return json_decode($json_file,true);
    } else {
        echo 'SERVER ERROR - POSTS FILE NOT FOUND AT: '.$posts.'<BR>';
    }
}

