<?php
session_start();
require_once($GLOBALS['readCSVDirectory']);
require_once($GLOBALS['userHandlingDirectory']);

// if debug mode is on, show current login information at the top of the page
if ($GLOBALS['debug']){
    echo '<div style="background-color: white">development mode is on. ';
    if (isset($_SESSION['userID']) && $_SESSION['userID'] != 'guest'){
        echo 'signed in as user #'.$_SESSION['userID']; echo ' - '.get_user($_SESSION['userID'])['email']; echo ' - ROLE: '; echo isUserAdmin($_SESSION['userID'])?'ADMIN':'USER';
    } else{
        echo 'not currently logged in';
    }
    echo '</div>';
}

// check if user is logged in
function isLoggedIn(){
    return isset($_SESSION['userID']) && $_SESSION['userID'] != 'guest'?true:false;
}

// force login
function forceLogin(){
    header("Location: ../../".$GLOBALS['loginPage']);
}

// query a given user and return a bool if they're an admin or not
function isUserAdmin($user_id){
    return get_user($user_id)['role']=='0';
}