<?php
// GLOBAL FILE -- creates database object, connects with user session, holds file path globals to avoid hardcoded file paths and directory issues

// file paths ------------------------------
$GLOBALS['rootName']='ASE230-CreateHub-midterm';
// get root directory for directory calculation, avoids relative directory issues
function getRootDirectory(){
    $dirExp=explode('\\',dirname(__FILE__));
    array_unshift($dirExp,''); // stops "undefined array key" error
    $rootPath='';
    // parse file path and end it at the root directory folder name
    for($i=1;$i<count($dirExp);$i++){
        if ($dirExp[$i-1]!=$GLOBALS['rootName']){
            $rootPath=$rootPath.$dirExp[$i].'/';
        } else {
            break;
        }
    }
    return $rootPath;
}
$rootPath=getRootDirectory();

// library directory globals
$GLOBALS['userHandlingDirectory']=$rootPath.'lib/users.php';
$GLOBALS['postHandlingDirectory']=$rootPath.'lib/posts.php';
$GLOBALS['generalDirectory']=$rootPath.'lib/general.php';
$GLOBALS['authDirectory']=$rootPath.'lib/auth/auth.php';
$GLOBALS['adminAuthDirectory']=$rootPath.'lib/auth/admin.php';
$GLOBALS['readCSVDirectory']=$rootPath.'scripts/readCSV.php';
$GLOBALS['readJSONDirectory']=$rootPath.'scripts/readJSON.php';

// page directory globals

// create database -----------------------------
$GLOBALS['databaseDirectory']=$rootPath.'lib/database.php';
require_once ($GLOBALS['databaseDirectory']);
$db=new Database();
//echo $db->testConnection()?'database conected':'database not connected';
// start session --------------------------------
require_once ($GLOBALS['authDirectory']);