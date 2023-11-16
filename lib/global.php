<?php
// GLOBAL FILE -- creates database object, connects with user session, holds file path globals to avoid hardcoded file paths and directory issues
require_once ('./auth/auth.php');
require_once ('./database.php');

// file paths ------------------------------
// get root directory for directory calculation, avoids relative directory issues
function getRootDirectory(){
    
}
$rootDir='';

$GLOBALS['userHandlingDirectory']='lib/users.php';


// create database -----------------------------
$db=new Database();