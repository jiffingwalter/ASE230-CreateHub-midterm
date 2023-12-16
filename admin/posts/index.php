<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$all_posts=get_all_posts();
?>
<head>
    <title>Post Manager</title>
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
            <a href="../index.php"><< Back to dashboard</a>
            <hr>
            <h3>All User Posts</h3>
            <div class="table_stage">
                <table class="admin-table" border="1" cellpadding="5" cellspacing="2">
                    <td><a href="create.php">Create new post</a></td>
                </table>
                <!-- table listing all available products -->
                <table class="admin-table" border="1" cellpadding="5" cellspacing="2" style="width:900px">
                    <?php 
                    if(count($all_posts)<1){ ?>
                        <tr><td style="text-align:center">No posts currently available</td></tr>
                    <?php
                    }else{ ?>
                        <!-- column labels -->
                        <tr>
                            <td><p><b>Post PID:</b></p></td>
                            <td><p><b>Post Author/UID:</p></td>
                            <td><p><b>Post Title:</p></td>
                            <td colspan="3"><p><b>Post Options:</p></td>
                        </tr>
                        <!-- table entries -->
                        <?php
                        for($i=0;$i<count($all_posts);$i++){ 
                            $user=get_post_author($all_posts[$i]['pid']);
                            ?>
                            <tr>
                                <td class="table_col_id"><p><b><?=$all_posts[$i]['pid']?></b></p></td>
                                <td class="table_col_author"><p><?=$user['email']?> [<?=$user['uid']?>]</p></td>
                                <td class="table_col_title"><p><?=$all_posts[$i]['title']?></p></td>
                                <td class="table_col_details"><p><a href="details.php?index=<?=$all_posts[$i]['pid']?>">View details</a></p></td>
                                <td class="table_col_edit"><p><a href="edit.php?index=<?=$all_posts[$i]['pid']?>">Edit</a></p></td>
                                <td class="table_col_delete"><p><a href="delete.php?index=<?=$all_posts[$i]['pid']?>">Delete</a></p></td>
                            </tr>
                    <?php  }
                    } ?>
                </table>
            </div>
            <br><hr><br>
            <a href='./testPosts.php'>Post testing area</a>
        </div>
    </header>
</body>