<?php
require_once($GLOBALS['generalDirectory']);
require_once($GLOBALS['readCSVDirectory']);

function get_all_users(){
    return readCSV($GLOBALS['userDataDirectory']);
}

// gets single user and returns their info
function get_user($user_id){
    $users=get_all_users();
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($users);$i++){
        if ($users[$i]['uid'] == $user_id){
            $id_found=true;
            break;
        }
    }

    // return user info if found. return empty user and throw error if user not found
    if ($id_found){
        return $users[$i];
    } else {
        display_system_error('Could not find user with ID #'.$user_id.' inside user data file',$_SERVER['SCRIPT_NAME']);
        return $users[0];
    }
}

// accepts an id and tells you if that user exists in the system or not
function does_user_exist($user_id){
    $users=get_all_users();
    $id_found=false;

    // step through user data and compare ids until a match
    for ($i=0;$i<count($users);$i++){
        if ($users[$i]['uid'] == $user_id){
            $id_found=true;
            break;
        }
    }

    // return if user was found or not
    return ($id_found)?true:false;
}

function generate_user_id(){
    $users=get_all_users();
    $id_is_unique=false;
    // step through user data and check ids
    while(!$id_is_unique){
        $new_id=rand(100000,999999);

        for ($i=0;$i<count($users);$i++){
            if ($users[$i]['uid'] == $new_id){
                $id_is_unique=false;
                break; // found non-unique id, break out with var set to false and generate another
            }
            $id_is_unique=true; // these ids were unique, set to true so while loop doesn't run again if for loop completes
        }
    }
    return $new_id;
}

function create_user($info_in){
    try{
        // append new user to end of user data file, 
        $users_updated=fopen($GLOBALS['userDataDirectory'],'a');
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
        return [true,$user_id];
    } catch (Exception $ex){
        return [false,$ex];
    }
}

// edits a user by going through and matching
function edit_user($info_in,$user){
    try{
        $users_existing=get_all_users();
        $users_updated=fopen($GLOBALS['userDataDirectory'],'w');

        // step through existing users, update infomation if their id matches and the field has been changed
        for ($row=0;$row < count($users_existing);$row++){
            if ($users_existing[$row]['uid'] == $user['uid']){
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
                $fields['uid'].
                PHP_EOL);
        }
        fclose($users_updated);
        return true;
    } catch (Exception $ex){
        return $ex;
    }
}

function delete_user($info_in){
    $users_existing=get_all_users();
    $users_updated=fopen($GLOBALS['userDataDirectory'],'w');

    // put column attributes, then rewrite users EXCEPT if its the user to delete
    fputcsv($users_updated,['email','password','date_created','id'],';');
    foreach ($users_existing as $fields){
        // rewrite user info in file if current id doesn't match deletion id
        if($fields['uid']!=$info_in['uid']){
            fwrite($users_updated,implode(';',$fields)."\n");
        }else{ // id matches, delete user files and directory. don't add them to file
            // get images directory content; if dir isn't empty, delete each image
            $img_directory=get_directory_contents('../../data/users/'.$info_in['uid'].'/images');
            if(count($img_directory)>0){
                foreach($img_directory as $image){
                    unlink('../../data/users/'.$info_in['uid'].'/images/'.$image);
                }
            }
            rmdir('../../data/users/'.$info_in['uid'].'/images');
            unlink('../../data/users/'.$info_in['uid'].'/portfolio.json');
            unlink('../../data/users/'.$info_in['uid'].'/posts.json');
            $delete_success=rmdir('../../data/users/'.$info_in['uid']);
        }
    }
    fclose($users_updated);

    // display success message and stop loading page to avoid nulls
    ($delete_success)?display_message('Account '.$info_in['uid'].' has been deleted'):'';
    echo '<a href="./index.php">Back to index</a><br>';
    die;
}

// validates info for account creation
function validate_user_signup($info_in){
    //check if email exists
    $users = readCSV($GLOBALS['userDataDirectory']);
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
    $users = readCSV($GLOBALS['userDataDirectory']);

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