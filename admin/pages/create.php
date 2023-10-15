<?php
require_once('pages.php');
if (isset($_POST['name'])){
    create_page($_POST);
}
?>

<head>
    <title>Create a new page</title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>

<body>
    <h1>Page Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Create a new page</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>">
        <label for="name">Page Name:</label><br>
        <input style="width:300px;" type="text" name="name">
        <button type="submit">Create Page</button><br>
        <label for="code">Page Content:</label><br>
        <textarea style="width:100%;height:490px" name="code"></textarea><br><br>
    </form>
</body>