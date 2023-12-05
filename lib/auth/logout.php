<?php
require_once('../../lib/global.php');
require_once('../general.php');
// unset and destroy session, then redirect to home
$_SESSION=array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// display message and give back option
display_message('You have successfully logged out.');
?>
<a href="../../data/pages/index.php"><button>Return home</button></a>
