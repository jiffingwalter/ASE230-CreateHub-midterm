<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../scripts/readJSON.php');
$extensions = ['png', 'jpg', 'jpeg', 'PNG'];
?>
<body style="margin-top: 100px; background-color: black; color: white;">
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="name">Portfolio Name</label><br>
        <input type="text" name="name" required><br>

        <label for="category">Cetegory of Work</label><br>
        <input type="text" name="category" required><br>

        <label for="image">Upload an Image</label><br>
        <input type="file" name="image" accept=".png, .jpg, .jpeg"><br>

        <input type="hidden" name="date_created" value="<?=date("Y-m-d")?>"><br>
        <input type="submit" value="Create Post">
    </form>
</body>