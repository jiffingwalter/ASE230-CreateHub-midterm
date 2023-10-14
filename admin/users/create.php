<?php
require_once('./users.php');
$users=get_all_users();

if (isset($_POST['username']) && isset($_POST['password'])){
    validate_info($_POST);
    return;
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
            <label for="username">Email:</label><br>
            <input type="email" name="username"><br>
            <label for="Password">Password:</label><br>
            <input type="password" name="password"><br>
            <label for="ConfirmPassword">Confirm Password:</label><br>
            <input type="password" name="confirmPassword"><br><br>
            <button type="submit">Create Account</button>
    </form>
</body>