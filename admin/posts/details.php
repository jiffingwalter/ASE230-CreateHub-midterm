<?php
require_once('./posts.php');
$uid=$_GET['index'];
$post=get_post($uid);
?>

<head>
    <title>Viewing post UID #<?=$all_posts[$index]['uid']?></title>
</head>

<body>
    <h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Viewing product entry <?=$all_posts[$index]['uid']?></h2>
    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="edit.php?index=<?=$all_posts[$i]['uid']?>">Edit</a></td>
        <td><a href="delete.php?index=<?=$all_posts[$i]['uid']?>">Delete</a></td>
    </table>
    <hr>
    <table border="1" cellpadding="5" cellspacing="2">
        <tr><p><?=$all_posts[$i]['author']?></p></tr>
        <tr><p><?=$all_posts[$i]['author']?></p></tr>
    </table>
</body>