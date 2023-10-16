<?php
session_start();
file_exists('../lib/general.php')?require_once('../lib/general.php'):require_once('../../lib/general.php');
// allow user only if they're logged in with a session and an admin
$admins=get_admins();
$id_found=false;
$userID=isset($_SESSION['userID'])?$_SESSION['userID']:'';

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

function get_admins() {
    $admin_file=file_exists('../data/users/admins.csv')?'../data/users/admins.csv':'../../data/users/admins.csv';
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
