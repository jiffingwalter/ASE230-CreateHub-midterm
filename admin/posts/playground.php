<?php
echo '<pre>';
require_once('../../lib/posts.php');
$all_posts=get_all_posts();

// print all posts
foreach($all_posts as $post){
    var_dump($post);
}