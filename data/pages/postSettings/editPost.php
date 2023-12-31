<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../../themes/head.php');
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$posts=get_user_posts($userID);

if(count($_POST)>0){
    //update title
    if($_POST['title'] != ''){
        $updateTitle=db->preparedQuery('UPDATE posts SET title = ? WHERE pid = ?',[$_POST['title'], $posts[$index]['pid']]);
    }
    
    //update body
    if($_POST['content'] != ''){
        $updateTitle=db->preparedQuery('UPDATE posts SET content = ? WHERE pid = ?',[$_POST['content'], $posts[$index]['pid']]);
    }

    //update tags
    if($_POST['tags'] != ''){
        parse_tags_in($_POST['tags'], $posts[$index]['pid']);
    }
    header('location: ../userPage.php');
}

?>
<link href="../../../dist/css/styles.css" rel="stylesheet" />
<nav style="text-align: left;">
    <a class="btn btn-primary" style="color: white" href="../../../data/pages/index.php">CreateHub Home</a>
</nav>
<body style="background-color: black; color: white; text-align:center">
<form method="POST" action="editPost.php?index=<?=$index?>">
    <label for="title">Title:</label><br>
    <input type="text" name="title"><br><br>

    <label for="content">Post Body:</label><br>
    <textarea name="content" rows="10" cols="50"></textarea><br><br>

    <label for="tags">Post Tags (separated by commas):</label> <br>
    <input type="text" name="tags"> <br><br>

    <input type="text" name="index" value="<?=$index?>" hidden>

    <input type="submit" value="Update Post">
</form>
</body>