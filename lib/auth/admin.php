<?php
require_once('auth.php');
session_start();
access_control($_SESSION['userID']);

// see if user is an admin and can view the file/page
function access_control($session){
    if(is_user_admin($session)){
        // user is in admin file, let em hang out
    }else{
        // user isn't in admin file, kick em out
        display_system_error('Access denied',$_SERVER['SCRIPT_NAME']);
        die;
    }
}

// read through admin file and check if the user signed in is an admin
function is_user_admin($user_id){
    $admins=readCSV('../data/users/admins.csv');
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($admins);$i++){
        if ($admins[$i]['id'] == $user_id){
            $id_found=true;
            break;
        }
    }
    return $id_found;
}