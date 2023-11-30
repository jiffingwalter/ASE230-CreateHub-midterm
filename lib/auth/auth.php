<?php
session_start();
require_once($GLOBALS['readCSVDirectory']);
require_once($GLOBALS['userHandlingDirectory']);

// if debug mode is on, show information at the top of the page
if ($GLOBALS['debug']){
    echo 'development mode is on. current user: '; echo isset($_SESSION['userID'])?$_SESSION['userID']:'not logged in';
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

function validateUser($email, $password){
    $users = get_all_users();
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['email'] && password_verify($password, $users[$i]['password'])){
            return true;
        }
    }
    return false;
}

function validateUserEmail($email){
    $users = get_all_users();
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['email']){
            return false;
        }
    }
    return true;
}

function getUserIndex($email){
    $users = get_all_users();
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['email']){
            return $users[$i]['uid']; 
        }
    }
}