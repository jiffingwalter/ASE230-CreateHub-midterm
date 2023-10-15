<?php
echo '<pre>';
require_once('../../lib/posts.php');
$all_posts=get_all_posts();

function test_all_posts($all_posts){
    echo 'testing all posts...<br>';
    foreach($all_posts as $post){
        var_dump($post);
        echo '<br>';
    } echo '<br>';
}

get_post('p000000');