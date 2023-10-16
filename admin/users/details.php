<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/users.php');
require_once('../../lib/posts.php');
$user=get_user($_GET['index']);
$user_posts=get_user_posts($user['id']);
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
            <td><?= count($user_posts) ?></td></tr>
        <tr>
    </table>
    <hr>
    <h3>User Content</h3>
    <!-- posts -->
    <table border="1" cellpadding="5" cellspacing="2">
        <tr>
            <td><b>Posts:</b></td>
            <td style="min-width:512px"><table border="1" cellpadding="5" cellspacing="2" style="width:100%">
            <?php 
            if(count($user_posts)<1){ ?>
                <tr><td style="text-align:center">No posts by this user</td></tr>
            <?php
            }else{ ?>
                <!-- column labels -->
                <tr>
                    <td><b>Post UID:</b></td>
                    <td><p><b>Post Title:</p></td>
                    <td colspan="3"><p><b>Post Options:</p></td>
                </tr>
                <!-- table entries -->
                <?php
                for($i=0;$i<count($user_posts);$i++){ ?>
                    <tr>
                        <td class="table_col_id"><b><?=$user_posts[$i]['uid']?></b></td>
                        <td class="table_col_title"><p><?=$user_posts[$i]['title']?></p></td>
                        <td class="table_col_details"><a href="details.php?index=<?=$user_posts[$i]['uid']?>">View details</a></td>
                        <td class="table_col_edit"><a href="edit.php?index=<?=$user_posts[$i]['uid']?>">Edit</a></td>
                    </tr>
            <?php  }
            } ?></td>
        <tr>
    </table>
        <tr>
            <td><b>Portfolio:</b></td>
            <td><b>[big box with user's portfolio]</b></td>
        <tr>
</body>