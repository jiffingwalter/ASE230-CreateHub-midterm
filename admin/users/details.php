<?php
require_once('../../lib/users.php');
$user=get_user($_GET['index']);
?>

<head>
    <title>Viewing User ID #<?=$user['id']?></title>
</head>

<body>
    <h1>User Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Viewing User ID #<?=$user['id']?></h2>
    <h3>User Details</h3>
    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="edit.php?index=<?=$user['id']?>">Edit</a></td>
        <td><a href="delete.php?index=<?=$user['id']?>">Delete</a></td>
    </table>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>ID:</b></td>
            <td><?=$user['id']?></td></tr>
            <td><b>Email:</b></td>
            <td><?=$user['email']?></td></tr>
            <td><b>Date created:</b></td>
            <td><?=$user['date_created']?></td></tr>
            <td><b>Posts:</b></td>
            <td><?= '[count of user posts function call goes here]' ?></td></tr>
        <tr>
    </table>
    <hr>
    <h3>User Content</h3>
    <!-- posts -->
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>Posts:</b></td>
            <td><b>[big box with all user's posts]</b></td>
        <tr>
        <tr>
            <td><b>Portfolio:</b></td>
            <td><b>[big box with user's portfolio]</b></td>
        <tr>
    </table>
</body>