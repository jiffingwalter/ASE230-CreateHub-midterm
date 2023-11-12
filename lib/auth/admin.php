<?php
session_start();
require_once(file_exists('../lib/general.php')?'../lib/general.php':'../../lib/general.php'); // directory issue hack
// allow user only if they're logged in with a session and an admin
$id_found=false;
$userID=isset($_SESSION['userID'])?$_SESSION['userID']:'';
$admin_file=get_admin_file();
$admins=get_admins($admin_file);

// step through user data and compare ids until a match

for ($i=0;$i<count($admins);$i++){
    if ($admins[$i]['id'] == $userID){
        $id_found=true;
        break;
    }
}
if(!$id_found){
    display_system_error('Access denied',$_SERVER['SCRIPT_NAME']);
    die;
}

function get_admin_file(){
    return file_exists('../data/users/admins.csv')?'../data/users/admins.csv':'../../data/users/admins.csv'; // directory issue hack
}

function get_admins($admin_file) {
    $fp=fopen($admin_file,'r');
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
    $admins=get_admins(get_admin_file());

    // step through user data and compare ids until a match
    for ($i=0;$i<count($admins);$i++){
        if ($admins[$i]['id'] == $user_id){
            return true;
        }
    }
    return false;
}

// adds a user to the admin list
function add_admin($user_id) {
    $admin_file=get_admin_file();
    $admins=get_admins($admin_file);
    // check if id is already in the list, return if so
    
    // add id if it wasn't found
    $admin_updated=fopen($admin_file,'a');
    fputs($admin_updated,$user_id.PHP_EOL);
    fclose($admin_updated);
}

function remove_admin($user_id) {
    $admin_file=get_admin_file();
    $admins=get_admins($admin_file);

    // rewrite file with ids UNLESS an id is not in the list
    $admin_updated=fopen($admin_file,'w');
    fputs($admin_updated,'id'.PHP_EOL);
    foreach($admins as $id){
        if ($id['id']!=$user_id) fputs($admin_updated,$id['id'].PHP_EOL);
    }
    fclose($admin_updated);
}