<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);

if(isset($_POST['yes'])){
    if($_POST['yes']){
        //delete account
        $delete=db->preparedQuery('DELETE FROM users WHERE uid = ?',[$userID]);
        header("Location: ../../../".$GLOBALS['loginPage']);
    }
}
?>

<body id="page-top" style="background-color: black; color: white;">
    <div style="margin-top: 100px; text-align: center">
    <h1>Are you sure you want to delete your account?</h1><br><br>
        <form method="POST">
            <label for="yes">YES</label>
            <input type="checkbox" name="yes" id="yes"><br><br>
            <input type="submit">
            <br><br>
        </form>
        <a href=../userPage.php><< BACK TO PAGE</a>
        <br><br><br>
    </div>
</body>
