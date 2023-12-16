<?php
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['userHandlingDirectory']);
$users=get_all_users();

if(count($_POST)>0){
    if (isset($_POST['email']) && isset($_POST['password'])){
        // run user info through validation and create account if validation returns successful
        if(validate_user_signup($_POST)){
            create_user($_POST)?
            display_message('Account has been created'):
            '';
        }
        echo '<a href="./create.php">Create another</a><br>
            <a href="./index.php">Back to index</a><br>';
        return;
    }
}
?>

<head>
    <title>Creating new user</title>
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
        </div>
    </header>
</body>