<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['userHandlingDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$users=get_all_users();

// if author id is set, create post. error if not
if (isset($_POST['author'])){
    if(strlen($_POST['author'])>0) {
        $new_pid=create_post($_POST,$_FILES);
        ($new_pid)?display_message('Created post PID #'.$new_pid.'!'):die;
        echo '<a href="./details.php?index='.$new_pid.'">Go to post '.$new_pid.'</a><br>
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

            <h2>Creating New Post</h2>
            <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <label for="title">Title:</label> <br>
                <input type="text" name="title"> <br>

                <label for="author">Author:</label> <br>
                <select name="author">
                    <option value=""></option>
                    <?php foreach($users as $user){
                        echo '<option value="'.$user['uid'].'">'.$user['email'].' ['.$user['uid'].']</option>';
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
        </div>
    </header>
</body>