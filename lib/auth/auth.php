<?php
require_once('../../scripts/readCSV.php');
if(!isset($_SESSION['userID'])){
    if (!strpos($_SERVER['REQUEST_URI'], 'lib/auth/login.php')) {
        header("Location: ../../lib/auth/login.php");
    }
}
function validateUser($email, $password){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
        // array testing, it keeps only returning the first two accounts for some reason?
        //echo $i.'. '.$users[$i]['email'].' = '.$email.($email == $users[$i]['email']?' true, ':' false, ');
        //echo $i.'. '.$users[$i]['password'].' = '.$password.(password_verify($password, $users[$i]['password'])?' true<br>':' false<br>');
        if($email == $users[$i]['email'] && password_verify($password, $users[$i]['password'])){
            return true;
        }
    }
    return false;
}

function validateUserEmail($email){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['email']){
            return false;
        }
    }
    return true;
}

function getUserIndex($email){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['email']){
            return $i; 
        }
    }
}

// read through admin file and check if the user signed in is an admin
function isUserAdmin($user_id){
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