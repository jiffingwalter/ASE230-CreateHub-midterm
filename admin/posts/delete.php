<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$pid=$_GET['index'];
$post=get_post($pid);

if (isset($_POST['pid'])){
    delete_post($pid)?display_message('Deleted post PID#'.$pid):'';
    echo '<a href="./index.php">Back to post manager</a><br>';
    die;
}
?>

<head>
    <title>Delete Post PID #<?=$pid?></title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>
<body>
<h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2 style="margin-bottom:0px">Are you sure you want to delete Post PID #<?=$pid?>?</h2><br>
    <p>This cannot be undone</p>
    <form method="POST">
        <input type="hidden" name="pid" value="<?= $pid ?>">
        <button>Delete post</button>
    </form>
</body>