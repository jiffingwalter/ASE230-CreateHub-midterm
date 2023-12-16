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
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../../dist/assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../../dist/css/styles.css" rel="stylesheet" />
    <style>
        html{
            background-color: #4e4239;
        }
    </style>
</head>
<body>
    <header class="masthead set-center">
        <div class="admin-stage">
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
        </div>
    </header>
</body>