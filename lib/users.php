<?php
require_once('general.php');
require_once('../../scripts/readCSV.php');

function get_all_users(){
    return readCSV('../../data/users/users.csv');
}

// gets single user
function get_user($user_id){
    $users=get_all_users();
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($users);$i++){
        if ($users[$i]['id'] == $user_id){
            $id_found=true;
            break;
        }
    }

    // return user id if found. return empty user and throw error if user not found
    if ($id_found){
        return $users[$i];
    } else {
        display_system_error('Could not find user with ID #'.$user_id.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return $users[0];
    }
}

function generate_user_id(){
    $users=get_all_users();
    $id_is_unique=false;
    // step through user data and check ids
    while(!$id_is_unique){
        $new_id="u".rand(100000,999999);

        for ($i=0;$i<count($users);$i++){
            if ($users[$i]['id'] == $new_id){
                $id_is_unique=false;
                break; // found non-unique id, break out with var set to false and generate another
            }
            $id_is_unique=true; // these ids were unique, set to true so while loop doesn't run again if for loop completes
        }
    }
    return $new_id;
}

function create_user($info_in){
    // append new user to end of user data file, 
    $users_updated=fopen('../../data/users/users.csv','a');
    // generate user id and append new user info
    $user_id=generate_user_id();
    fputs($users_updated,$info_in['email'].';'.
        password_hash($info_in['password'],PASSWORD_DEFAULT).';'.
        get_timestamp().';'.
        $user_id.
        PHP_EOL);
    fclose($users_updated);
    
    // create user dependencies
    mkdir('../../data/users/'.$user_id, 0755);
    mkdir('../../data/users/'.$user_id.'/images', 0755);
    file_put_contents('../../data/users/'.$user_id.'/posts.json', json_encode([], JSON_PRETTY_PRINT));
    file_put_contents('../../data/users/'.$user_id.'/portfolio.json', json_encode([], JSON_PRETTY_PRINT));
}

// edits a user by going through and matching
function edit_user($info_in,$user){
    $users_existing=get_all_users();
    $users_updated=fopen('../../data/users/users.csv','w');

    // step through existing users, update infomation if their id matches and the field has been changed
    for ($row=0;$row < count($users_existing);$row++){
        if ($users_existing[$row]['id'] == $user['id']){
            if (strlen($info_in['new_password'])>0){ // if new password was given, update password and hash it
                $users_existing[$row]['password']=password_hash($info_in['new_password'],PASSWORD_DEFAULT);
            }
            $users_existing[$row]['email']=$info_in['new_email'];
        }
    }
    // put column attributes, then rewrite each line with updated user and close file
    fputcsv($users_updated,['email','password','date_created','id'],';');
    foreach ($users_existing as $fields){
        fputs($users_updated,$fields['email'].';'.
            $fields['password'].';'.
            $fields['date_created'].';'.
            $fields['id'].
            PHP_EOL);
    }
    fclose($users_updated);
    return true;
}

function delete_user($info_in){
    $users_existing=get_all_users();
    $users_updated=fopen('../../data/users/users.csv','w');

    // put column attributes, then rewrite users EXCEPT if its the user to delete
    fputcsv($users_updated,['email','password','date_created','id'],';');
    foreach ($users_existing as $fields){
        // rewrite user info in file if current id doesn't match deletion id
        if($fields['id']!=$info_in['id']){
            fwrite($users_updated,implode(';',$fields)."\n");
        }else{
            // id matches, delete user files and directory. don't add them to file
            unlink('../../data/users/'.$info_in['id'].'/portfolio.json');
            unlink('../../data/users/'.$info_in['id'].'/posts.json');
            $delete_success=rmdir('../../data/users/'.$info_in['id']);
        }
    }
    fclose($users_updated);

    // display success message and stop loading page to avoid nulls
    ($delete_success)?display_message('Account '.$info_in['id'].' has been deleted'):'';
    echo '<a href="./index.php">Back to index</a><br>';
    die;
}

// validates info for account creation
function validate_user_signup($info_in){
    //check if email exists
    $users = readCSV('../../data/users/users.csv');
    $id_found=false;
    for($i=0;$i<count($users);$i++){
        if($info_in['email'] == $users[$i]['email']){
            $id_found=true;
            break;
        }
    }
    // validate password if user doesn't already exist, error if they do
    if(!$id_found){
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
    $users = readCSV('../../data/users/users.csv');

    //check if email exists (if its not the account being edited)
    $id_found=false;
    for($i=0;$i<count($users);$i++){
        if($info_in['new_email'] == $users[$i]['email'] && $info_in['id'] != $users[$i]['id']){
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