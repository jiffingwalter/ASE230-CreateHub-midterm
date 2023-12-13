<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/nav.php');
require_once($GLOBALS['databaseDirectory']);

if(count($_POST)>0){
    //change username
    if($_POST['changeUsername'] != ''){
        $checkUsernames=db->preparedQuery('SELECT * FROM users WHERE name=?',[$_POST['changeUsername']]);
        if(db->resultFound($checkUsernames)){
            display_error('The username you entered is already in use');
        }else{
            //change username in db
            $updateUsername=db->preparedQuery("UPDATE users SET name = ? WHERE uid = ?",[$_POST['changeUsername'], $userID]);
            display_error('Username has been updated');
        }
    }
    //change password
    if($_POST['changePassword'] != ''){
        //check if curent password is password
        $checkPassword=db->preparedQuery('SELECT password FROM users WHERE uid=?',[$userID]);

        if(password_verify($_POST['currentPassword'], $checkPassword['password'])){
            //set password in db
            $changePassword=db->preparedQuery('UPDATE users SET password = ? WHERE uid = ?',[password_hash($_POST['changePassword'], PASSWORD_DEFAULT),$userID]);
            display_error('Password has been updated');
        }else{
            display_error('The password you entered is incorrect');
        }
    }
    //change email
    if($_POST['changeEmail'] != ''){
        $checkEmails=db->preparedQuery('SELECT email FROM users WHERE email = ?',[$_POST['changeEmail']]);
        if(db->resultFound($checkEmails)){
            display_error('The email you entered is already in use');
        }else{
            //change email in db
            $changeEmail=db->preparedQuery('UPDATE users SET email = ? WHERE uid = ?',[$_POST['changeEmail'], $userID]);
            display_error('Email has been updated');
        }
    }
}
?>

<body id="page-top" style="background-color: black; color: white;">
    <div style="margin-top: 100px; text-align: center">
    <h1>User Settings:</h1><br><br>
        <form method="POST">
            <h3><label for="changeUsername">Change Username:</label></h3>
            <input type="text" name="changeUsername"><br><br>
            <h3>Change password:</h3>
            <h4><label for="currentPassword">Current password:</label></h4>
            <input type="password" name="currentPassword"><br>
            <h4><label for="changePassword">New password:</label></h4>
            <input type="password" name="changePassword"><br><br>
            <h3>Change email:</h3>
            <h4><label for="changeEmail">New Email:</label></h4>
            <input type="email" name="changeEmail"><br>
            <h1><input type="submit" class="btn btn-primary" style="color: white"></h1>
        </form>
        <br><br><br>
        <h3>Delete Account:</h3>
        <h1><a class="btn btn-primary" style="color: white" href="./userSettings/delete.php">Delete Account</a></h1>
    </div>
</body>
