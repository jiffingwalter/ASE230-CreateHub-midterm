<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once($GLOBALS['readJSONDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
if(count($_POST)>0){
    // append user id to POST array, run portfolio creation function
    $_POST['uid']=$userID;
    create_portfolio($_POST, $_FILES);
    header('Location: ./portfolio.php');
}
?>
<body style="margin-top: 100px; background-color: black; color: white;">
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="name">Portfolio Name</label><br>
        <input type="text" name="name" required><br>

        <label for="category">Category of Work</label><br>
        <input type="text" name="category" required><br>

        <label for="images">Upload Images</label><br>
        <input type="file" name="images[]" accept=".png, .jpg, .jpeg" multiple required><br>

        <input type="hidden" name="user_id" value="<?=$userID?>">
        <input type="submit" value="Create Portfolio">
    </form>
</body>