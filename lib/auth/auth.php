<?php
require_once('../../scripts/readCSV.php');
session_start();

// check if user is logged in
function isLoggedIn(){
    return isset($_SESSION['userID'])?true:false;
}

// force login
function forceLogin(){
    header("Location: ../../lib/auth/login.php");
}

// read through admin file and check if the user signed in is an admin
function isUserAdmin($user_id){
    $admins=readCSV('../../data/users/admins.csv');
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

function validateUser($email, $password){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
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
            return $users[$i]['id']; 
        }
    }
}