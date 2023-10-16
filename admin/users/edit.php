<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/users.php');
$user=get_user((count($_GET) > 0)?$_GET['index']:$_POST['id']);

// edit account
if (isset($_POST['new_email']) && isset($_POST['new_password'])){
    // validate new info, pass new info to edit user function if it's good. return whatever response and print back buttons
    if(validate_user_edit($_POST,$user)){
        (edit_user($_POST,$user))?display_message('Account has been updated'):'';
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
    <title>Editing User Information for ID #<?=$user['id']?></title>
</head>

<body>
    <h1>User Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Editing User Information for ID #<?=$user['id']?></h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="new_email">Email:</label><br>
        <input type="email" name="new_email" value="<?= $user['email'] ?>"><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" name="new_password"><br>
        <label for="confirm_new_password">Confirm New Password:</label><br>
        <input type="password" name="confirm_new_password"><br><br>
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <button type="submit">Save Changes</button>
    </form> <hr>
    <?php if(!$show_confirm_delete){ ?>
        <form method="POST">
            <input type="hidden" name="confirm_delete" value="confirm_delete">
            <button>Delete entry</button>
        </form>
    <?php } ?>
    <?php if($show_confirm_delete){ ?>
        <form method="POST">
            <p>Are you sure you want to delete this entry? This cannot be undone.</p>
            <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="confirm_delete" value="confirm_delete">
            <button>Delete entry</button>
        </form>
    <?php } ?>
</body>