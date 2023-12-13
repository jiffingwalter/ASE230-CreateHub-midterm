<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$posts=get_user_posts($userID);
$userPost = $posts[$index];

if(count($_POST)>0){
    if($userPost['has_attachment'] == '1'){
        //get attachment
        $attachment = db->preparedQuery('SELECT file_name FROM attachments WHERE pid = ?',[$userPost['pid']]);
        
        //delete local image
        if(file_exists('../../users/'.$userID.'/images/'.$attachment['file_name'])){
            unlink('../../users/'.$userID.'/images/'.$attachment['file_name']);
        }
    }
    //delete db
    $deletePost = db->preparedQuery('DELETE FROM posts WHERE pid = ?',[$userPost['pid']]);
    header('Location: ../userPage.php');
}
?>

<body id="page-top" style="background-color: black; color: white;">
    <div style="margin-top: 100px; text-align: center">
    <h1>Are you sure you want to delete this post? This cannot be undone.</h1><br><br>
        <form method="POST">
            <label for="yes">YES</label>
            <input type="checkbox" name="yes" id="yes">
            <br><br>
            <input type="submit">
            <br><br>
        </form>
        <a href="./editPortfolio.php?index=<?=$index?>"><< BACK TO PORTFOLIO</a>
        <br><br><br>
    </div>
</body>