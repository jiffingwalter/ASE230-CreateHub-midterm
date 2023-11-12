<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/posts.php');
$uid=$_GET['index'];
$post=get_post($uid);

if (isset($_POST['uid'])){
    delete_post($uid,true)?display_message('Deleted post #'.$uid):'';
    echo '<a href="./index.php">Back to post manager</a><br>';
    die;
}
?>

<head>
    <title>Delete Post UID #<?=$uid?></title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>
<body>
<h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2 style="margin-bottom:0px">Are you sure you want to delete Post UID #<?=$uid?>?</h2><br>
    <p>This cannot be undone</p>
    <form method="POST">
        <input type="hidden" name="uid" value="<?= $uid ?>">
        <button>Delete post</button>
    </form>
</body>