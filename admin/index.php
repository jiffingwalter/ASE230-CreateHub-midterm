<?php
require_once('../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);

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

<?php // database testing area
echo '<pre><hr>database testing area<br><br><br>';


