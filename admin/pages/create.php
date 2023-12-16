<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once('pages.php');
if (isset($_POST['name'])){
    create_page($_POST);
}
?>

<head>
    <title>Create a new page</title>
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
            <a href="index.php"><< Back</a>
            <hr>

            <h2>Create a new page</h2>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <label for="name">Page Name:</label><br>
                <input style="width:300px;" type="text" name="name">
                <button type="submit">Create Page</button><br>
                <label for="code">Page Content:</label><br>
                <textarea style="width:900px;height:490px" name="code"></textarea><br><br>
            </form>
        </div>
    </header>
</body>