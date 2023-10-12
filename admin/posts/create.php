<?php
// note to self - make sure to make "author" option as a dynamically filled dropdown list of existing users
require_once('./posts.php');

if (isset($_POST['index'])){
    create_product($_POST);
    echo '<pre>';
    print_r($_POST);
    return;
}
?>

<head>
    <title>Creating New Post</title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>
<body>
    <h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Creating New Post</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title:</label> <br>
        <input type="text" name="title"> <br>

        <label for="author">Author:</label> <br>
        <input type="text" name="author"> <br>
        
        <label for="content">Post Content:</label> <br>
        <textarea type="text" name="content" style="width:512px;height:128px"></textarea><br><br>

        <label for="tags">Tags (separated by commas):</label> <br>
        <input type="text" name="tags"> <br>

        <button type="submit">Save changes</button>
    </form>
</body>