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

    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="edit.php?index=<?=$user['id']?>">Edit</a></td>
        <td><a href="delete.php?index=<?=$user['id']?>">Delete</a></td>
    </table>
    <hr>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>Email:</b></td>
            <td><?=$user['username']?></td></tr>
        <tr>
    </table>
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