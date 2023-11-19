<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once('pages.php');
$pages=get_pages();
?>

<head>
    <title>Page Manager</title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>

<body>
    <h1>Page Manager</h1>
    <a href="../index.php"><< Back to dashboard</a><hr>

    <table border="1" cellpadding="5" cellspacing="2">
        <td><a href="create.php">Create a new page</a></td>
    </table>
    <table border="1" cellpadding="5" cellspacing="2">
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
</body>