<?php
require_once($GLOBALS['generalDirectory']);
require_once($GLOBALS['readCSVDirectory']);

// returns list of all users
function get_all_users(){
    return db->queryAll('SELECT * FROM users');
}

// gets single user by their id and returns their info
function get_user($user_id){
    $user=db->preparedQuery('SELECT * FROM users WHERE uid=?',[$user_id]);
    if (db->resultFound($user)){
        return $user;
    } else {
        display_system_error('Could not find user with ID #'.$user_id.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return db->query('SELECT * FROM users WHERE uid=0');
    }
}

// looks up a user in the db by email and returns their id if found, and false if not found
function get_user_id($email){
    $uid=db->preparedQuery('SELECT uid FROM users WHERE email=:email',[
        "email"=>$email
    ])['uid'];
    if ($GLOBALS['debug']) echo '<br>returning user #'.$uid.'<br>';
    return $uid;
}

// accepts an id and returns true if that user exists in the system or not
function does_user_exist($user_id){
    $user=db->preparedQuery('SELECT * FROM users WHERE uid=?',[$user_id]);
    if (db->resultFound($user)){
        return true;
    } else {
        return false;
    }
}

// DEPRECATED
function generate_user_id(){
    $user_ids=db->queryAll('SELECT uid FROM users');
    $id_is_unique=false;
    // step through user data and check ids
    while(!$id_is_unique){
        $new_id=rand(100000,999999);

        for ($i=0;$i<count($user_ids);$i++){
            if ($user_ids[$i]['uid'] == $new_id){
                $id_is_unique=false;
                break; // found non-unique id, break out with var set to false and generate another
            }
            $id_is_unique=true; // these ids were unique, set to true so while loop doesn't run again if for loop completes
        }
    }
    return $new_id;
}

// user modification ----------------------------------------------------------------------------------------------
// creates a new user and returns true + user_id if successful or false + the error if not
function create_user($info_in){
    try{
        // generate new user id and push the info into the database
        db->preparedQuery('INSERT INTO users VALUES (,:name,:email,:password,CURRENT_TIMESTAMP,:role)',[
            'name'=>$info_in['name'],
            'email'=>$info_in['email'],
            'password'=>password_hash($info_in['password'],PASSWORD_DEFAULT),
            'role' => 1 // default role is ALWAYS user. can be changed by admin manually or through verification after creation
        ]);
        $user_id=db->query('SELECT LAST_INSERT_ID()')['LAST_INSERT_ID()'];
        
        // create user dependencies
        mkdir('../../data/users/'.$user_id, 0755);
        mkdir('../../data/users/'.$user_id.'/images', 0755);
        return $user_id;
    } catch (Exception $ex){
        return false;
    }
}

// edits a user by looking up their info the database and replacing all text fields
function edit_user($info_in){
    try{
        // insert new values into user
        db->preparedQuery('UPDATE `users` SET `name`=:name,`email`=:email,`password`=:password,`role`=:role WHERE `uid`=:uid',[
            'uid'=>$info_in['uid'],
            'name'=>$info_in['name'],
            'email'=>$info_in['email'],
            'password'=>password_hash($info_in['password'],PASSWORD_DEFAULT),
            'role' => isset($info_in['role'])?$info_in['role']:1 // set role if its given, otherwise default to normal user
        ]);

        return true;
    } catch (Exception $ex){
        return $ex;
    }
}

function delete_user($info_in){
    try{
        // delete user & their portfolios if they are found
        db->preparedQuery('DELETE FROM users WHERE `uid`=:uid',[$info_in['uid']]);

        // delete user files and directory
        // get images directory content; if dir isn't empty, delete each image
        $img_directory=get_directory_contents('../../data/users/'.$info_in['uid'].'/images');
        if(count($img_directory)>0){
            foreach($img_directory as $image){
                unlink('../../data/users/'.$info_in['uid'].'/images/'.$image);
            }
        }
        rmdir('../../data/users/'.$info_in['uid'].'/images');
        $delete_success=rmdir('../../data/users/'.$info_in['uid']);
        return $delete_success;
    } catch (Exception $ex){
        return $ex;
    }
}

// user validation ----------------------------------------------------------------------------------------------
// validates info for account creation
function validate_user_signup($info_in){
    //check if email exists
    $users = get_all_users();
    $email_found=false;
    for($i=0;$i<count($users);$i++){
        if($info_in['email'] == $users[$i]['email']){
            $id_found=true;
            break;
        }
    }
    // validate password if user doesn't already exist, error if they do
    if(!$email_found){
        if(strlen($info_in['password'])<1){ // check if blank
            display_error('Password cannot be blank');
        }elseif($info_in['password'] != $info_in['confirmPassword']){ // check if passwords match
            display_error('Passwords do not match');
        }else{ // it passes password and email validation, make the account
            create_user($info_in);
            return true;
        }
    }else{
        display_error('Email is already in use');
    }
}

// validates info for editing accounts
function validate_user_edit($info_in,$user){
    $users = get_all_users();

    //check if email exists (if its not the account being edited)
    $id_found=false;
    for($i=0;$i<count($users);$i++){
        if($info_in['new_email'] == $users[$i]['email'] && $info_in['uid'] != $users[$i]['uid']){
            $id_found=true;
            break;
        }
    }
    // validate password if user doesn't already exist, error if they do
    if(!$id_found){
        if(strlen($info_in['new_password'])>0 && $info_in['new_password'] != $info_in['confirm_new_password']){ // check if passwords match (if password was given)
            display_error('Passwords do not match');
        }else{ // it passes password and email validation, return true
            return true;
        }
    }else{
        display_error('Email is already in use');
    }
}