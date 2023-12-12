<?php
require_once('../global.php');
require_once($GLOBALS['userHandlingDirectory']);
$showSignUp=True;
if(isset($_SESSION['email'])) $showSignUp=false;
if(count($_POST)>0){
    //Signup
    if(isset($_POST['email']) && isset($_POST['password'])){
        // attempt to validate user's info, if its good give success message
        if(validate_user_signup($_POST)){
            create_user($_POST)?
            display_message('Your account has been created, please log in'):
            '';
            $showSignUp=false;
        }
    }
    //Login
    if(isset($_POST['LoginEmail']) && isset($_POST['LoginPassword'])){
        if (validate_user_login($_POST['LoginEmail'], $_POST['LoginPassword'])){
            // assign user id to session variable and redirect to index
            $_SESSION['userID'] = get_user_id($_POST['LoginEmail']);
            header("Location: ../../".$GLOBALS['indexPage']);
            die();
        }else{
            display_error('Incorrect email/password, please try again or create an account');
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
            <input type="password" name="LoginPassword"><br><br>
            <input type="submit" value="Login">
        </form>
        <hr>
        <?php
        if($showSignUp){
        ?>
            <h3>Sign Up</h3>
            <form method="POST">
                <label for="name">Enter your username:</label><br>
                <input type="text" name="name"><br>
                <label for="email">Enter your email:</label><br>
                <input type="email" name="email"><br>
                <label for="Password">Enter a password:</label><br>
                <input type="password" name="password"><br>
                <label for="ConfirmPassword">Confirm your password:</label><br>
                <input type="password" name="confirmPassword"><br><br>
                <input type="submit" value="Sign Up">
            </form>
        <?php
        }?>
    </body>
</html>