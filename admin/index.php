<?php
require_once('../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);

?>
<head>
    <title>CreateHub Admin Dashboard</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../dist/assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../dist/css/styles.css" rel="stylesheet" />
    <style>
        html{
            background-color: #4e4239;
        }
    </style>
</head>
<body>
    <header class="masthead set-center">
        <div class="admin-stage">
            <br>
            <h1 class="text-white">CreateHub Admin Dashboard</h1>
            <a href="../data/pages/index.php"><< Back to main</a><br><br>
            <hr>
            <h4><a href="./users/index.php">User management</a></h4>
            <h4><a href="./posts/index.php">Posts management</a></h4>
            <h4><a href="./pages/index.php">Page management</a></h4>
        </div>
    </header>
</body>