<?php
require_once('./posts.php');
$all_posts=get_all_posts();
?>
<head>
    <title>Post Manager</title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>
<body>
    <h1>Post Manager</h1>
    <a href="../admin.php"><< Back</a>
    <hr>
    <h3>All User Posts</h3>
    <div class="table_stage">
        <table border="1" cellpadding="5" cellspacing="2">
            <td><a href="create.php">Create new post</a></td>
        </table>
        <!-- table listing all available products -->
        <table border="1" cellpadding="5" cellspacing="2" style="width:100%">
            <?php 
            if(count($all_posts)<1){ ?>
                <tr><td style="text-align:center">No posts currently available</td></tr>
            <?php
            }else{ ?>
                <!-- column labels -->
                <tr>
                    <td><b>Post UID:</b></td>
                    <td><p><b>Post Author:</p></td>
                    <td><p><b>Post Title:</p></td>
                    <td colspan="3"><p><b>Post Options:</p></td>
                </tr>
                <!-- table entries -->
                <?php
                for($i=0;$i<count($all_posts);$i++){ ?>
                    <tr>
                        <td class="table_col_id"><b><?=$all_posts[$i]['uid']?></b></td>
                        <td class="table_col_author"><p><?=$all_posts[$i]['author']?></p></td>
                        <td class="table_col_title"><p><?=$all_posts[$i]['title']?></p></td>
                        <td class="table_col_details"><a href="details.php?index=<?=$all_posts[$i]['uid']?>">View details</a></td>
                        <td class="table_col_edit"><a href="edit.php?index=<?=$all_posts[$i]['uid']?>">Edit</a></td>
                        <td class="table_col_delete"><a href="delete.php?index=<?=$all_posts[$i]['uid']?>">Delete</a></td>
                    </tr>
            <?php  }
            } ?>
        </table>
    </div>
</body>