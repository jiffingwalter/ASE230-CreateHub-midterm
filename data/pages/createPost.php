<?php
require_once('../../lib/auth/auth.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../lib/posts.php');

if(count($_POST)>0){
    create_post($_POST,$_FILES);
    header("Location: userPage.php");
    die();
}
?>
<body style="background-color: black; margin-top: 70px; color: white;">
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title:</label><br>
        <input type="text" name="title" required><br>

        <label for="content">Post Body:</label><br>
        <textarea name="content" rows="10" cols="50" required></textarea><br>

        <label for="image">Upload an Image (Optional):</label><br>
        <input type="file" name="image" accept=".png, .jpg, .jpeg"><br>

        <label for="tags">Post Tags (separated by commas):</label> <br>
        <input type="text" name="tags"> <br><br>

        <input type="hidden" name="user_id" value="<?=$userID?>"><br>
        <input type="submit" value="Create Post">
    </form>
</body>