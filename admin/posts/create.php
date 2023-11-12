<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/posts.php');
require_once('../../lib/users.php');
$users=get_all_users();

// if author id is set, create post. error if not
if (isset($_POST['author'])){
    if(strlen($_POST['author'])>0) {
        $new_uid=create_post($_POST,$_FILES);
        (isset($new_uid))?display_message('Created post #'.$new_uid.'!'):'';
        echo '<a href="./details.php?index='.$new_uid.'">Go to post '.$new_uid.'</a><br>
            <a href="./create.php">Create another post</a><br>
            <a href="./index.php">Back to post manager</a><br>';
        die;
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
    <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title:</label> <br>
        <input type="text" name="title"> <br>

        <label for="author">Author:</label> <br>
        <select name="author">
            <option value=""></option>
            <?php foreach($users as $user){
                echo '<option value="'.$user['id'].'">'.$user['email'].' ['.$user['id'].']</option>';
            } ?>
        </select><br>
        
        <label for="content">Post Content:</label> <br>
        <textarea type="text" name="content" style="width:512px;height:128px"></textarea><br>

        <label for="attachments">Attachment:</label> <br>
        <input type="file" name="attachments" accept=".png, .jpg, .jpeg"><br>

        <label for="tags">Tags (separated by commas):</label> <br>
        <input type="text" name="tags"> <br><br>

        <button type="submit">Create Post</button>
    </form>
</body>