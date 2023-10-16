<?php
require_once('../../lib/auth/admin.php');
require_once('../../lib/users.php');
$users=get_all_users();

if(count($_POST)>0){
    if (isset($_POST['email']) && isset($_POST['password'])){
        if(validate_user_signup($_POST)){
            display_message('Account has been created');
        }
        echo '<a href="./create.php">Create another</a><br>
            <a href="./index.php">Back to index</a><br>';
        return;
    }
}
?>

<head>
    <title>Creating new user</title>
</head>

<body>
    <h1>User Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Create new user account</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <label for="email">Email:</label><br>
            <input type="email" name="email"><br>
            <label for="Password">Password:</label><br>
            <input type="password" name="password"><br>
            <label for="ConfirmPassword">Confirm Password:</label><br>
            <input type="password" name="confirmPassword"><br><br>
            <button type="submit">Create Account</button>
    </form>
</body>