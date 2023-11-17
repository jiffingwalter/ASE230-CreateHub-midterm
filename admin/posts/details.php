<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$pid=$_GET['index'];
$post=get_post($pid);
?>

<head>
    <title>Viewing Post PID #<?=$post['pid']?></title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>

<body>
    <h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Viewing Post PID #<?=$post['pid']?></h2>

    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="edit.php?index=<?=$post['pid']?>">Edit</a></td>
        <td><a href="delete.php?index=<?=$post['pid']?>">Delete</a></td>
    </table>
    <hr>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>Title:</b></td>
            <td><?=$post['title']?></td></tr>
        <tr>
            <td><b>Author:</b></td>
            <td><?=get_post_author($post['author'])?> [<?=$post['author']?>]</td></tr>
        <tr>
            <td><b>Content:</b></td>
            <td><?=$post['content']?></td></tr>
        <tr>
            <td><b>Tags:</b></td>
            <td><?= parse_tags_out($post['tags']) ?></td></tr>
        <tr>
            <td><b>Date Created:</b></td>
            <td><?=$post['date_created']?></td></tr>
        <tr>
            <td><b>Date Last Edited:</b></td>
            <td><?=$post['last_edited']?></td></tr>
    </table>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr><td><b>Post Attachments: </b></td></tr>
        <tr><td>
        <?php
            if ($post['attachments']['error'] == 0){ ?>
                <img src="../../data/users/<?=$post['author']?>/images/<?=$post['attachments']['name']?>" style="max-width: 1024px"><br>
                <p><b>Location: </b>../../data/users/<?=$post['author']?>/images/<?=$post['attachments']['name']?></p> <?php
            } else { ?>
                <p>No attachment provided</p>
            <?php } ?>
        </tr></td>
    </table>
</body>