<?php
require_once('auth.php');
$showSignUp=True;
if(isset($_SESSION['email'])) $showSignUp=false;
if(count($_POST)>0){
    //Signup
    if(isset($_POST['email']) && isset($_POST['password'])){
        //check if email exists
        if(validateUserEmail($_POST['email'])){
            //check password == confirmPassword
            if($_POST['password'] == $_POST['confirmPassword']){
                //process data
                $fp=fopen('../../data/users/users.csv','a+');
                fputs($fp,$_POST['email'].';'.password_hash($_POST['password'],PASSWORD_DEFAULT).PHP_EOL);
                fclose($fp);
                echo 'your account has been created, please log in';
                $showSignUp=false;
            }else{
                echo '<h2> passwords do not match please try again </h2>';
            }
        }else{
            echo '<h2> User already exists please log in </h2>';
        }   
    }
    //Login
    if(isset($_POST['LoginEmail']) && isset($_POST['LoginPassword'])){
        if (validateUser($_POST['LoginEmail'], $_POST['LoginPassword'])){
            //go to index
            session_start();
            $_SESSION['userID'] = getUserIndex($_POST['LoginEmail']);
            header("Location: ../../data/pages/index.php?");
            die();
        }else{
            echo '<h2> Incorrect username/password please try again, or create an account';
            $showSignUp=True;
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