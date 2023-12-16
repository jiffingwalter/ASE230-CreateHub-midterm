<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$pid=$_GET['index'];
$post=get_post($pid);
$author=get_post_author($post['pid']);
$attachments=get_attachments($post['pid']);
?>

<head>
    <title>Viewing Post PID #<?=$post['pid']?></title>
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

            <h2>Viewing Post PID #<?=$post['pid']?></h2>

            <table class="admin-table" border="1" cellpadding="5" cellspacing="2">
                <td><a href="edit.php?index=<?=$post['pid']?>">Edit</a></td>
                <td><a href="delete.php?index=<?=$post['pid']?>">Delete</a></td>
            </table>
            <table class="admin-table" border="1" cellpadding="5" cellspacing="2" style="width:700px">
                <tr>
                    <td><b>Title:</b></td>
                    <td><?=$post['title']?></td></tr>
                <tr>
                    <td><b>Author:</b></td>
                    <td><?=$author['email']?> [<?=$author['uid']?>]</td></tr>
                <tr>
                    <td><b>Content:</b></td>
                    <td><?=$post['content']?></td></tr>
                <tr>
                    <td><b>Tags:</b></td>
                    <td><?=parse_tags_out($post['pid'])?></td></tr>
                <tr>
                    <td><b>Date Created:</b></td>
                    <td><?=$post['date_created']?></td></tr>
                <tr>
                    <td><b>Date Last Edited:</b></td>
                    <td><?=$post['last_edited']?></td></tr>
            </table>
            <table class="admin-table" border="1" cellpadding="5" cellspacing="2" style="max-width:700px">
                <tr><td><b>Post Attachments: </b></td></tr>
                <tr><td>
                <?php
                    if ($attachments){?>
                        <img src="../../data/users/<?=$author['uid']?>/images/<?=$attachments[0]['file_name']?>" style="max-width: 1024px"><br>
                        <p><b>Location: </b>../../data/users/<?=$author['uid']?>/images/<?=$attachments[0]['file_name']?></p> <?php
                    } else { ?>
                        <p><sub>(No attachment provided)</sub></p>
                    <?php } ?>
                </tr></td>
            </table>
        </div>
    </header>
</body>