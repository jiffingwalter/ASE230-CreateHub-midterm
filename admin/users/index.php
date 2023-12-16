<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['userHandlingDirectory']);
$users=get_all_users();
?>

<head>
    <title>User Manager</title>
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
            <h1>User Manager</h1>
            <a href="../index.php"><< Back to dashboard</a>
            <hr>
            <table class="admin-table" border="1" cellpadding="5" cellspacing="2" style="min-width:100px">
                <td><a href="create.php">Create new account</a></td>
            </table>
            <table class="admin-table" border="1" cellpadding="5" cellspacing="2" style="width:900px">
                <tr>
                    <td><b>User ID:</b></td>
                    <td><p><b>Email:</p></td>
                    <td colspan="2"><p><b>Options:</p></td>
                </tr>
                <?php
                if (count($users)<1){ ?>
                    <tr><td style="text-align:center">No user accounts!</td></tr>
                <?php
                }else {
                    for($i=0;$i<count($users);$i++){ ?>
                        <tr>
                            <td><b><?= $users[$i]['uid'] ?></b></td>
                            <td><b><?=$users[$i]['email']?></td>
                            <td style="width:80px"><a href="details.php?index=<?= $users[$i]['uid'] ?>">View details</a></td>
                            <td><a href="edit.php?index=<?= $users[$i]['uid'] ?>">Edit</a></td>
                        </tr>
                <?php }
                } ?>
            </table>
        </div>
    </header>
</body>