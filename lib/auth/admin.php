<?php
require_once($GLOBALS['generalDirectory']);
require_once($GLOBALS['authDirectory']);
// module for checking user admin status and restricting access to nonadmin pages
$id_found=false;
$userID=isset($_SESSION['userID'])?$_SESSION['userID']:'';

// check if user is admin, deny access if not


if(!isUserAdmin($userID)){
    display_system_error('Access denied',$_SERVER['SCRIPT_NAME']);
    die;
}

// adds a user to the admin list
function add_admin($user_id) {
    return db->preparedQuery('UPDATE users SET role=0 WHERE uid=:uid',[
        'uid'=>$user_id
    ]);
}

function remove_admin($user_id) {
    return db->preparedQuery('UPDATE users SET role=1 WHERE uid=:uid',[
        'uid'=>$user_id
    ]);
}