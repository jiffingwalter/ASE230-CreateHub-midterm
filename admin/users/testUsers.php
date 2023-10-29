<?php // this file is to test user creation, editing, post handling, and deletion
require_once('../../lib/auth/admin.php');
require_once('../../lib/users.php');
require_once('../../lib/posts.php');

$testUserInfo=[
    'email'=>'testUser1@email.com',
    'password'=>'password'
];

function testUserCreation($infoIn){
    // attempt user creation, return true and the new ID if successful, return error if not
    $newUser=create_user($infoIn);
    if ($newUser[0]==false and !does_user_exist($newUser[1])){
        return false;
    }else{
        return $newUser;
    }
}

function testUserEdit($userIn){
    // attempt to edit user's email and password, validate with template and check edit date
    $infoIn=[
        'email'=>'testUserNewEmail@email.com',
        'password'=>'password2'
    ];
    edit_user($infoIn,$userIn);
}