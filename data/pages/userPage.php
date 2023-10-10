<?php
$index = $_GET['index'];
require_once('../themes/head.php');
?>

<body id="page-top" style="background-color: black;">
    <?php
    require_once('../themes/nav.php');
    ?>
    <div style="margin-top: 70px;">
        <h1><a class="nav-link" href="./portfolio.php?index=<?=$index?>">Portfolio</a></h1>
    </div>
</body>
