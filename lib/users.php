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
        $user_id.';'.
        PHP_EOL);
    fclose($users_updated);
    
    // create user dependencies
    mkdir('../../data/users/'.$user_id, 0755);
    file_put_contents('../../data/users/'.$user_id.'/posts.json', json_encode([], JSON_PRETTY_PRINT));
    file_put_contents('../../data/users/'.$user_id.'/portfolio.json', json_encode([], JSON_PRETTY_PRINT));
}

// edits a user by going through and matching
function edit_users($info_in){
    echo 'Saving changes to user '.$info_in['id'].' ...';
    $users_existing=get_all_users();
    $users_updated=fopen('../data/users/users.csv','w');

    // step through existing users, update infomation if their id matches
    for ($row=0;$row < count($users_existing);$row++){
        if ($users_existing[$row]['id'] == $info_in['id']){
            $users_existing[$row]['password']=$info_in['password'];
            $users_existing[$row]['email']=$info_in['email'];
        }
    }
    // put column attributes, then write each line of users and close file
    fputcsv($users_updated,['email','password'],';');
    foreach ($users_existing as $fields){
        fwrite($users_updated,$fields['email'].';'.password_hash($fields['password'],PASSWORD_DEFAULT)."\n");
    }
    fclose($users_updated);
    header('Location: detail.php?index='.$info_in['index']); //redirect back to index
    die;
}

function delete_users($info_in){
    echo 'Deleting user '.$info_in['id'].'...';
    $users_existing=get_all_users();
    $users_updated=fopen('../data/users/users.csv','w');

    // put column attributes, then rewrite users EXCEPT if its the user to delete
    fputcsv($users_updated,['email','password'],';');
    foreach ($users_existing as $fields){
        fwrite($users_updated,$fields['email']==$info_in['email']?"":implode(';',$fields)."\n");
        // delete user's directory
        
    }
    fclose($users_updated);
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