<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../scripts/readJSON.php');
if(count($_POST)>0){
    $userPosts=readJSON('../users/'.$userID.'/posts.json');
    $post=['title'=>$_POST['title'], 'content'=>$_POST['content'], 'image'=>$_POST['image'], 'date_created'=>$_POST['date_created']];
    $userPosts[count($userPosts)]=$post;
    file_put_contents('../users/'.$userID.'/posts.json', json_encode($userPosts, JSON_PRETTY_PRINT));
    //put image in a folder
    if(isset($_POST['image'])){
        file_put_contents('../users/'.$userID.'/images',$_POST['image']);
    }
    header("Location: userPage.php");
    die();
}
?>
<body style="background-color: black; margin-top: 70px; color: white;">
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title</label><br>
        <input type="text" name="title" required><br>

        <label for="content">Post Body</label><br>
        <textarea name="content" rows="10" cols="50" required></textarea><br>

        <label for="image">Upload an Image</label><br>
        <input type="file" name="image" accept="image/*"><br>

        <input type="hidden" name="date_created" value="<?=date("Y-m-d")?>"><br>
        <input type="submit" value="Create Post">
    </form>
</body>