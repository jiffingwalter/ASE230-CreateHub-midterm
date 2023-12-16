<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once('pages.php');
$pages=get_pages();
?>

<head>
    <title>Page Manager</title>
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
            <h1>Page Manager</h1>
            <a href="../index.php"><< Back to dashboard</a><hr>

            <table class="admin-table" border="1" cellpadding="5" cellspacing="2">
                <td><a href="create.php">Create a new page</a></td>
            </table>
            <table class="admin-table" border="1" cellpadding="5" cellspacing="2">
                <?php
                if(count($pages)<1){ ?>
                    <tr><td style="text-align:center">No enteries!</td></tr>
                <?php }else{
                    for($i=0;$i<count($pages);$i++){
                    ?>
                    <tr>
                        <td><b><?=$i?>.</b></td>
                        <td style="min-width:250px"><p><?=$pages[$i]?></p></td>
                        <td><a href="edit.php?index=<?=$i?>">Edit</a></td>
                    </tr>
                    <?php
                    }
                }
                ?>
            </table>
        </div>
    </header>
</body>