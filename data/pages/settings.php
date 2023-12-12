<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/nav.php');
require_once($GLOBALS['databaseDirectory']);

if(count($_POST)>0){
    //change username
    if(isset($_POST['changeUsername'])){
        $checkUsernames=db->preparedQuery('SELECT * FROM users WHERE name=?',[$_POST['changeUsername']]);
        if(db->resultFound($checkUsernames)){
            display_system_error('User already exists with this username. Please choose a different one.',$_SERVER['SCRIPT_NAME']);
        }else{
            $updateUsername=db->preparedQuery("UPDATE users SET name = ? WHERE uid = ?",[$_POST['changeUsername'], $userID]);
        }
    }
    //change password
    if(isset($_POST['changePassword'])){
        //check if curent password is password

    }
    //change email
    if(isset($_POST['changeEmail'])){

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