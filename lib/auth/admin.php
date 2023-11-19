<?php
require_once($GLOBALS['generalDirectory']); // directory issue hack
// allow user only if they're logged in with a session and an admin
$id_found=false;
$userID=isset($_SESSION['userID'])?$_SESSION['userID']:'';
$admins=get_admins();

// step through user data and compare ids until a match

for ($i=0;$i<count($admins);$i++){
    if ($admins[$i]['uid'] == $userID){
        $id_found=true;
        break;
    }
}
if(!$id_found){
    display_system_error('Access denied',$_SERVER['SCRIPT_NAME']);
    die;
}

function get_admins() {
    $fp=fopen($GLOBALS['adminListDirectory'],'r');
    $get_csv=fgetcsv($fp,0,';');
    $data=[];

    while($content=fgetcsv($fp,0,';')){
        if(count($get_csv)===count($content)){
            $data[]=array_combine($get_csv,$content);
        }
    }
    fclose($fp);
    return $data;
}

function is_user_admin($user_id){
    $admins=get_admins($GLOBALS['adminListDirectory']);

    // step through user data and compare ids until a match
    for ($i=0;$i<count($admins);$i++){
        if ($admins[$i]['uid'] == $user_id){
            return true;
        }
    }
    return false;
}

// adds a user to the admin list
function add_admin($user_id) {
    $admins=get_admins($GLOBALS['adminListDirectory']);
    // check if id is already in the list, return if so
    
    // add id if it wasn't found
    $admin_updated=fopen($GLOBALS['adminListDirectory'],'a');
    fputs($admin_updated,$user_id.PHP_EOL);
    fclose($admin_updated);
}

function remove_admin($user_id) {
    $admins=get_admins($GLOBALS['adminListDirectory']);

    // rewrite file with ids UNLESS an id is not in the list
    $admin_updated=fopen($GLOBALS['adminListDirectory'],'w');
    fputs($admin_updated,'id'.PHP_EOL);
    foreach($admins as $id){
        if ($id['uid']!=$user_id) fputs($admin_updated,$id['uid'].PHP_EOL);
    }
    fclose($admin_updated);
}