<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['userHandlingDirectory']);
$user=get_user((count($_GET) > 0)?$_GET['index']:$_POST['uid']);

// edit account
if (isset($_POST['new_email']) && isset($_POST['new_password'])){
    // validate new info, pass new info to edit user function if it's good. return whatever response and print back buttons
    if(validate_user_edit($_POST,$user)){
        (edit_user($_POST,$user))?display_message('Account has been updated'):'';
    }
    // update admin status
    if (isset($_POST['is_admin'])){
        add_admin($user['uid']);
    } else {
        remove_admin(($user['uid']));
    }
    echo '<a href="./index.php">Back to index</a><br>';
    return;
}

// delete account
$show_confirm_delete=false;
if (isset($_POST['confirm_delete'])){
    // show confirmation dialog, 
    $show_confirm_delete=true;
    if (isset($_POST['delete_id']) && isset($_POST['confirm_delete'])){
        delete_user($user);
    }
}
?>

<head>
    <title>Editing User Information for ID #<?=$user['uid']?></title>
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
            <h1>User Manager</h1>
            <a href="index.php"><< Back</a>
            <hr>

            <h2>Editing User Information for ID #<?=$user['uid']?></h2>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <label for="new_email">Email:</label><br>
                <input type="email" name="new_email" value="<?= $user['email'] ?>"><br>
                <label for="new_password">New Password:</label><br>
                <input type="password" name="new_password"><br>
                <label for="confirm_new_password">Confirm New Password:</label><br>
                <input type="password" name="confirm_new_password"><br>
                <label for="is_admin">Admin rights?</label><br>
                <input type="checkbox" id="is_admin" name="is_admin" value=<?php if(isUserAdmin($user['uid'])) echo 'Yes checked="checked"'; ?>>
                <br><br>
                <input type="hidden" name="id" value="<?= $user['uid'] ?>">
                <button type="submit">Save Changes</button>
            </form> <hr>
            <?php if(!$show_confirm_delete){ ?>
                <form method="POST">
                    <input type="hidden" name="confirm_delete" value="confirm_delete">
                    <button>Delete user</button>
                </form>
            <?php } ?>
            <?php if($show_confirm_delete){ ?>
                <form method="POST">
                    <p>Are you sure you want to delete this user? This cannot be undone.</p>
                    <input type="hidden" name="delete_id" value="<?= $user['uid'] ?>">
                    <input type="hidden" name="confirm_delete" value="confirm_delete">
                    <button>Delete user</button>
                </form>
            <?php } ?>
        </div>
    </header>
</body>