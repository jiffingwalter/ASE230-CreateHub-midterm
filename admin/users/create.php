<?php
require_once('./users.php');
$users=get_all_users();

if (isset($_POST['email']) && isset($_POST['password'])){
    //check if email exists
    if(validateUserEmail($_POST['username'])){
        //check password == confirmPassword
        if($_POST['password'] == $_POST['confirmPassword']){
            //process data
            create_user($_POST);
        }else{
            echo '<h2>Passwords do not match</h2>';
        }
    }else{
        echo '<h2>User already exists</h2>';
    }   
    
    return;
}
?>

<head>
    <title>Creating new user</title>
</head>

<body>
    <h1>Manage Awards Entries</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Create new award</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <label for="username">Email:</label><br>
            <input type="email" name="username"><br>
            <label for="Password">Password:</label><br>
            <input type="password" name="password"><br>
            <label for="ConfirmPassword">Confirm Password:</label><br>
            <input type="password" name="confirmPassword"><br><br>
            <button type="submit">Create Account</button>
    </form>
</body>