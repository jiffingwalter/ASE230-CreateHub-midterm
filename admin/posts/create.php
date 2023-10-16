<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/posts.php');
require_once('../../lib/users.php');
$users=get_all_users();

// if author id is set, create post. error if not
if (isset($_POST['user_id'])){
    if(strlen($_POST['user_id'])>0) {
        create_post($_POST,$_FILES);
        return;
    }else{
        display_error('Must select an author!');
    }
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

        <label for="user_id">Author:</label> <br> <!-- make this into drop down list that's filled with current users -->
        <select name="user_id">
            <option value=""></option>
            <?php foreach($users as $user){ ?>
                <option value="<?= $user['id'] ?>"><?= $user['email'] ?></option>
            <?php } ?>
        </select><br>
        
        <label for="content">Post Content:</label> <br>
        <textarea type="text" name="content" style="width:512px;height:128px"></textarea><br>

        <label for="attachments">Attachments:</label> <br>
        <input type="text" name="attachments"> <br>

        <label for="tags">Tags (separated by commas):</label> <br>
        <input type="text" name="tags"> <br><br>

        <button type="submit">Create Post</button>
    </form>
</body>