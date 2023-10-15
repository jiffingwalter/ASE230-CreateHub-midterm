<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../scripts/readJSON.php');
require_once('../../lib/posts.php');
$extensions = ['png', 'jpg', 'jpeg', 'PNG'];
if(count($_POST)>0){
    create_portfolio($_POST, $_FILES);
}
?>
<body style="margin-top: 100px; background-color: black; color: white;">
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="name">Portfolio Name</label><br>
        <input type="text" name="name" required><br>

        <label for="category">Cetegory of Work</label><br>
        <input type="text" name="category" required><br>

        <label for="images">Upload Images</label><br>
        <input type="file" name="images[]" accept=".png, .jpg, .jpeg" multiple><br>

        <input type="hidden" name="date_created" value="<?=date("Y-m-d")?>"><br>
        <input type="submit" value="Create Portfolio">
    </form>
</body>