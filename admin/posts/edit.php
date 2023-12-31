<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);

// get pid from index, get post info with pid and add author uid key
$pid=(count($_GET) >= 1)?$_GET['index']:$_POST['pid'];
$post=get_post($pid);
$post['author']=get_post_author($pid)['uid'];

require_once('../../lib/users.php');
$users=get_all_users();

$postAttachment=get_attachments($pid);
$postHasAttachment=($postAttachment);

//echo '<pre>';var_dump($post);var_dump($postAttachment);echo '</pre>';

// if author id is set, edit post. error if not
if (isset($_POST['author'])){
    if(strlen($_POST['author'])>0) {
        $_POST['author']=intval($_POST['author']); // make sure uid is an int and not a string
        edit_post($_POST,$_FILES)?display_message('Updated post PID #'.$pid.'!'):'';
        echo '<a href="./index.php">Back to posts manager</a><br>';
        echo '<a href="./edit.php?index='.$pid.'">Go to post PID #'.$pid.'</a><br>';
        die;
    }else{
        display_error('Must select an author!');
    }
}

// handle post deletion and confirmation dialog
$show_confirm_delete=false;
if (isset($_POST['confirm_delete'])){
    // show confirmation dialog
    $show_confirm_delete=true;
    if (isset($_POST['delete_id']) && isset($_POST['confirm_delete'])){
        delete_post($pid)?display_message('Deleted post PID #'.$pid):'';
        echo '<a href="./index.php">Back to posts manager</a><br>';
    }
}

// handle attachment deletion
if (isset($_POST['delete_attachment'])){
    delete_post_attachment($pid)?
        display_message('Deleted attachment successfully'):
        display_system_error('There was a problem deleting the attachment',$_SERVER['SCRIPT_NAME']);
    header('Location: edit.php?index='.$pid);
}

?>

<head>
    <title>Editing Post PID #<?=$post['pid']?></title>
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
            <h1>Post Manager</h1>
            <hr>
            <a href="index.php"><< Back</a>
            <hr>

            <h2>Editing Post PID #<?=$post['pid']?></h2>
            <p>Last edited: <?=$post['last_edited']?></p>
            <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <label for="title">Title:</label> <br>
                <input type="text" name="title" value="<?=$post['title']?>"> <br>

                <label for="author">Author:</label> <br>
                <select name="author">
                    <?php // select the current author of the post in the list by default
                    foreach($users as $user){
                        if($user['uid']==$post['author']){
                            echo '<option value="'.$user['uid'].'" selected>'.$user['email'].' ['.$user['uid'].']</option>';
                        } else {
                            echo '<option value="'.$user['uid'].'">'.$user['email'].' ['.$user['uid'].']</option>';
                        }
                    }
                    ?>
                </select><br>
                
                <label for="content">Post Content:</label> <br>
                <textarea type="text" name="content" style="width:512px;height:128px"><?=$post['content']?></textarea><br>

                <!-- TODO - add "delete attachment" button so it can be reset to blank -->
                <label for="current-attachment">Current Attachment:</label> <br>
                <div class="attachment-display">
                <?php if ($postHasAttachment){ ?>
                    <img src="../../data/users/<?=$post['author']?>/images/<?=$postAttachment[0]['file_name']?>" style="max-width: 256px"><br>
                <?php } else { ?>
                    <p>(no attachment)</p>
                <?php } ?>
                </div>
                <label for="attachments">New Attachment:</label> <br>
                <input type="file" name="attachments" accept=".png, .jpg, .jpeg"><br>

                <label for="tags">Tags (separated by commas):</label> <br>
                <input type="text" name="tags" style="width:512px" value="<?= parse_tags_out($pid)?>"> <br><br>

                <input type="hidden" name="pid" value="<?= $pid ?>">
                <button type="submit">Save changes</button><br><br>
            </form><hr>
            <?php if(!$show_confirm_delete){ ?>
                <form method="POST">
                    <input type="hidden" name="confirm_delete" value="confirm_delete">
                    <button>Delete post</button>
                </form>
            <?php } ?>
            <?php if($show_confirm_delete){ ?>
                <form method="POST">
                    <p>Are you sure you want to delete this post? This cannot be undone.</p>
                    <input type="hidden" name="delete_id" value="<?= $pid ?>">
                    <input type="hidden" name="confirm_delete" value="confirm_delete">
                    <button>Delete post</button>
                </form>
            <?php } ?>
            <?php if ($postHasAttachment){ ?>
            <form method="POST">
                <input type="hidden" name="delete_attachment" value="true">
                <button>Delete attachment</button>
            </form>
            <?php } ?>
        </div>
    </header>
</body>