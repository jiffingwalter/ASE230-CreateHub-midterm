<?php
require_once('auth.php');
$showSignUp=True;
if(isset($_SESSION['email'])) $showSignUp=false;
if(count($_POST)>0){
    echo '<pre>';
    print_r($_POST);
    if(isset($_POST['email'][0]) && isset($_POST['password'][0])){
        //check if email exists
        //check password == confirmPassword
        //process data
        $fp=fopen('../../data/users/users.csv','a+');
        fputs($fp,$_POST['email'].';'.password_hash($_POST['password'],PASSWORD_DEFAULT).PHP_EOL);
        fclose($fp);
        echo 'your account has been created, please sign in';
        $showSignUp=false;
    }
    if(isset($_POST['LoginEmail'][0]) && isset($_POST['LoginPassword'][0])){
        if(/*testauth here*/  true){
            header('Location: ../../data/pages/index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login/Sign Up</title>
    </head>

    <body>
        <h3>Log In</h3>
        <form method="POST">
            <label for="LoginEmail">Email:</label><br>
            <input type="email" name="LoginEmail"><br>
            <label for="LoginPassword">Password:</label><br>
            <input type="password" name="LoginPassword"><br>
            <input type="submit" value="Login">
        </form>
        <hr>
        <?php
        if($showSignUp){
        ?>
            <h3>Sign Up</h3>
            <form method="POST">
                <label for="Username">Enter your email:</label><br>
                <input type="email" name="email"><br>
                <label for="Password">Enter a password:</label><br>
                <input type="password" name="password"><br>
                <label for="ConfirmPassword">Confirm your password:</label><br>
                <input type="password" name="confirmPassword"><br>
                <input type="submit" value="Sign Up">
            </form>
        <?php
        }?>
    </body>
</html>