<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once('pages.php');

// handle editing page
if(isset($_POST['name'])){
    edit_page($_POST);
    display_message($_POST['name'].' has been updated');
    ?><a href="../../data/pages/<?=$_POST['name']?>">Go to edited page</a><br>
    <a href="index.php">Back to Index</a><?php
    return;
}else{
    (count($_GET)>=1)?$index=$_GET['index']:$index=$_POST['index'];
    $page=get_pages();
    $pageName=$page[$index];
    $pageContent=file_get_contents('../../data/pages/'.$pageName);
}

// handle page deletion
$show_confirm_delete=false;
if (isset($_POST['confirm_delete'])){
    // show confirmation dialog, delete on confirmation
    $show_confirm_delete=true;
    if (isset($_POST['delete_name']) && isset($_POST['confirm_delete'])){
        delete_page($_POST);
        display_message($_POST['delete_name'].' has been deleted');
        ?><a href="index.php">Back to index</a><?php
        return;
    }
}
?>
<head>
    <title>Editing page <?= $pageName ?></title>
</head>

<body>
    <h1>Page Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Editing page <?= $pageName ?></h2>

    <form method="POST" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>">
        <input type="hidden" name="name" value="<?=$pageName?>"><br>
        <button type="submit">Update Page</button><br>
        <label for="code">Page Content:</label><br>
        <textarea style="width:100%;height:450px" name="code"><?=$pageContent?></textarea><br><br>
    </form><hr>
    <?php if(!$show_confirm_delete){ ?>
        <form method="POST">
            <input type="hidden" name="confirm_delete" value="confirm_delete">
            <button>Delete page</button>
        </form>
    <?php } ?>
    <?php if($show_confirm_delete){ ?>
        <form method="POST">
            <p>Are you sure you want to delete this page? This cannot be undone.</p>
            <input type="hidden" name="delete_name" value="<?=$pageName?>">
            <input type="hidden" name="confirm_delete" value="confirm_delete">
            <button>Confirm delete page</button>
        </form>
    <?php } ?>
</body>