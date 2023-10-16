<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../../lib/posts.php');
$uid=$_GET['index'];
$post=get_post($uid);
?>

<head>
    <title>Viewing Post UID #<?=$post['uid']?></title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>

<body>
    <h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Viewing Post UID #<?=$post['uid']?></h2>

    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="edit.php?index=<?=$post['uid']?>">Edit</a></td>
        <td><a href="delete.php?index=<?=$post['uid']?>">Delete</a></td>
    </table>
    <hr>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>Title:</b></td>
            <td><?=$post['title']?></td></tr>
        <tr>
            <td><b>Author:</b></td>
            <td><?=$post['author']?></td></tr>
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
</body>