<?php
session_start();
require_once($GLOBALS['readCSVDirectory']);
require_once($GLOBALS['userHandlingDirectory']);

// if debug mode is on, show current login information at the top of the page
if ($GLOBALS['debug']){
    echo '<div style="background-color: white">development mode is on. signed in as user #'; echo isset($_SESSION['userID'])?$_SESSION['userID'].' - '.get_user($_SESSION['userID'])['email']:'not logged in';echo '</div>';
}

// check if user is logged in
function isLoggedIn(){
    return isset($_SESSION['userID'])?true:false;
}

// force login
function forceLogin(){
    header("Location: ../../".$GLOBALS['loginPage']);
}

// read through admin file and check if the user signed in is an admin
function isUserAdmin($user_id){
    $admins=readCSV($GLOBALS['adminListDirectory']);
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($admins);$i++){
        if ($admins[$i]['uid'] == $user_id){
            $id_found=true;
            break;
        }
    }
    return $id_found;
}