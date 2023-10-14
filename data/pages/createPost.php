<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../scripts/readJSON.php');
$extensions = ['png', 'jpg', 'jpeg'];
if(count($_POST)>0){
    $userPosts=readJSON('../users/'.$userID.'/posts.json');
    $post=['title'=>$_POST['title'], 'content'=>$_POST['content'], 'date_created'=>$_POST['date_created']];
    //put image in a folder
    if(isset($_FILES['image']) && $_FILES['image']['error'] != 4 && in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION),$extensions)){
        $img = $_FILES['image'];
        $img[count($img)] = pathinfo($img['name'], PATHINFO_EXTENSION);
        $post[count($post)] = $img;
        move_uploaded_file($img['tmp_name'],'../users/'.$userID.'/images/'.count($userPosts).'.'.pathinfo($img['name'], PATHINFO_EXTENSION));
    }else{
        $post[count($post)] = ['name' => 'noFileUploaded'];
    }
    $userPosts[count($userPosts)]=$post;
    file_put_contents('../users/'.$userID.'/posts.json', json_encode($userPosts, JSON_PRETTY_PRINT));
    header("Location: userPage.php");
    die();
}
?>
<body style="background-color: black; margin-top: 70px; color: white;">
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title</label><br>
        <input type="text" name="title" required><br>

        <label for="content">Post Body</label><br>
        <textarea name="content" rows="10" cols="50" required></textarea><br>

        <label for="image">Upload an Image</label><br>
        <input type="file" name="image" accept=".png, .jpg, .jpeg"><br>

        <input type="hidden" name="date_created" value="<?=date("Y-m-d")?>"><br>
        <input type="submit" value="Create Post">
    </form>
</body>