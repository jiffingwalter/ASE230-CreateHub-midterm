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
        if($email == $users[$i]['username'] && password_verify($password, $users[$i]['password'])){
            return true;
        }
    }
    return false;
}

function validateUserEmail($email){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['username']){
            return false;
        }
    }
    return true;
}

function getUserIndex($email){
    $users = readCSV('../../data/users/users.csv');
    for($i=0;$i<count($users);$i++){
        if($email == $users[$i]['username']){
            return $i; 
        }
    }
}
?>