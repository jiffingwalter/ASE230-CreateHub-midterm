<?php
//require_once('../lib/auth/admin.php');
session_start();
$userID=$_SESSION['userID'];

?>
<head>
    <title>CreateHub Admin Dashboard</title>
</head>
<body>
    <h1>CreateHub Admin Dashboard</h1>
    <a href="../data/pages/index.php"><< Back to main</a><br><br>
    <hr>
    <h4><a href="./users/index.php">User management</a></h4>
    <h4><a href="./posts/index.php">Posts management</a></h4>
    <h4><a href="./pages/index.php">Page management</a></h4>
</body>