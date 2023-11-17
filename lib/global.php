<?php
// GLOBAL FILE -- creates database object, connects with user session, holds file path globals to avoid hardcoded file paths and directory issues
// MUST have this directly referenced on ALL user pages a user will land on

// file paths ------------------------------
$GLOBALS['rootName']='ASE230-CreateHub-midterm'; // the name for the root folder, will need to be changed if we decide to change the git project name
// get root directory for directory calculation, avoids relative directory issues
function getRootDirectory(){
    $dirExp=explode('\\',__DIR__);
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
$GLOBALS['authAdminOnlyDirectory']=$rootPath.'lib/auth/admin.php';
$GLOBALS['readCSVDirectory']=$rootPath.'scripts/readCSV.php';
$GLOBALS['readJSONDirectory']=$rootPath.'scripts/readJSON.php';

// file directory globals
$GLOBALS['userDataDirectory']=$rootPath.'data/users/users.csv';
$GLOBALS['adminListDirectory']=$rootPath.'data/users/admins.csv';

// page globals (still relative for the "header" function)
$GLOBALS['loginPage']='lib/auth/login.php';
$GLOBALS['indexPage']='data/pages/index.php';

// other globals
$GLOBALS['attachmentExts']=['png', 'jpg', 'jpeg'];

// create database -----------------------------
$GLOBALS['databaseDirectory']=$rootPath.'lib/database.php';
require_once ($GLOBALS['databaseDirectory']);
$db=new Database();
//echo $db->testConnection()?'database connected':'database not connected';

// start session & handle authenticaton --------------------------------
require_once ($GLOBALS['authDirectory']);