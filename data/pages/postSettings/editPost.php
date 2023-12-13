<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$posts=get_user_posts($userID);

echo '<h1>'.$index.'</h1>';

if(count($_POST)>0){
    //update title
    if($_POST['title'] != ''){
        $updateTitle=db->preparedQuery('UPDATE posts SET title = ? WHERE pid = ?',[$_POST['title'], $posts[$index]['pid']]);
    }
    
    //update body
    if($_POST['content'] != ''){
    
    }

    //update tags
    if($_POST['tags'] != ''){
    
    }
}

?>

<form method="POST" action="editPost.php?index=<?=$index?>">
        <label for="title">Title:</label><br>
        <input type="text" name="title" required><br>

        <label for="content">Post Body:</label><br>
        <textarea name="content" rows="10" cols="50" required></textarea><br>

        <label for="tags">Post Tags (separated by commas):</label> <br>
        <input type="text" name="tags"> <br><br>

        <input type="text" name="index" value="<?=$index?>" hidden>

        <input type="submit" value="Update Post">
    </form>