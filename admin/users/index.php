<?php
require_once('./users.php');
$users=get_all_users();
?>

<head>
    <title>User Manager</title>
</head>

<body>
    <h1>User Manager</h1>
    <a href="../admin.php"><< Back to main</a>
    <hr>
    <table border="1" cellpadding="5" cellspacing="2" style="min-width:100px">
        <td><a href="create.php">Create new account</a></td>
    </table>
    <table border="1" cellpadding="5" cellspacing="2" style="width:700px">
        <?php
        if (count($users)<1){ ?>
            <tr><td style="text-align:center">No user accounts!</td></tr>
        <?php
        }else {
            for($i=0;$i<count($users);$i++){
                $user_index=getUserIndex($users[$i]['username']); ?>
                <tr>
                    <td><b><?= $user_index ?>.</b></td>
                    <td><p class="text-muted mb-5"><b><?=$users[$user_index]['username']?></td>
                    <td style="width:80px"><a href="details.php?index=<?=$user_index?>">View details</a></td>
                    <td><a href="edit.php?index=<?=$user_index?>">Edit</a></td>
                    <td><a href="delete.php?index=<?=$user_index?>">Delete</a></td>
                </tr>
        <?php }
        } ?>
    </table>
</body>