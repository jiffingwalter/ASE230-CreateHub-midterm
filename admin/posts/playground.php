<?php
echo '<pre>';
require_once('../../lib/posts.php');
$all_posts=get_all_posts();

echo 'testing all posts...<br>';
foreach($all_posts as $post){
    var_dump($post);
    echo '<br>';
} echo '<br>';