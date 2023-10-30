<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/posts.php');
$uid=(count($_GET) >= 1)?$_GET['index']:$_POST['uid'];
$post=get_post($uid);
require_once('../../lib/users.php');
$users=get_all_users();

// if author id is set, edit post. error if not
if (isset($_POST['author'])){
    if(strlen($_POST['author'])>0) {
        edit_post($_POST);
        return;
    }else{
        display_error('Must select an author!');
    }
}
?>

<head>
    <title>Editing Post UID #<?=$post['uid']?></title>
    <link href="../../dist/css/admin.scss" rel="stylesheet" />
</head>
<body>
    <h1>Post Manager</h1>
    <hr>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Editing Post UID #<?=$post['uid']?></h2>
    <p>Last edited: <?=$post['last_edited']?></p>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="title">Title:</label> <br>
        <input type="text" name="title" value="<?=$post['title']?>"> <br>

        <label for="author">Author:</label> <br>
        <select name="author">
            <?php // select the current author of the post in the list by default
            foreach($users as $user){
                if($user['id']==$post['author']){
                    echo '<option value="'.$user['id'].'" selected>'.$user['email'].'</option>';
                } else {
                    echo '<option value="'.$user['id'].'">'.$user['email'].'</option>';
                }
            }
            ?>
        </select><br>
        
        <label for="content">Post Content:</label> <br>
        <textarea type="text" name="content" style="width:512px;height:128px"><?=$post['content']?></textarea><br>

        <label for="tags">Tags (separated by commas):</label> <br>
        <input type="text" name="tags" style="width:512px" value="<?= parse_tags_out($post['tags'])?>"> <br><br>

        <input type="hidden" name="uid" value="<?= $uid ?>">
        <button type="submit">Save changes</button>
    </form><hr><br>
    <form method="GET" action="delete.php?index=<?=$uid?>">
        <input type="hidden" name="index" value="<?= $uid ?>">
        <button>Delete post</button>
    </form>
</body>