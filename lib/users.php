<?php
require_once('general.php');
require_once('../../scripts/readCSV.php');

function get_all_users(){
    return readCSV('../data/users/users.csv');
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
        display_error('Could not find user with ID #'.$user_id.' inside user data file',$_SERVER['SCRIPT_NAME']);
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

// validates info for account creation
function validate_info($info_in){
    //check if email exists
    if(validateUserEmail($_POST['username'])){
        // validate password
        if(strlen($_POST['password'])<1){ // check if blank
            echo '<h2>Password cannot be blank</h2>';
        }elseif($_POST['password'] != $_POST['confirmPassword']){ // check if passwords match
            echo '<h2>Passwords do not match</h2>';
        }else{
            create_user($info_in);
        }
    }else{
        echo '<h2>User already exists</h2>';
    }
    echo '<button onclick="history.go(-1);">Back</button>';
}

function create_user($info_in){
    // append new user to end of user data file, 
    $users_updated=fopen('../data/users/users.csv','a');
    // generate user id
    $user_id=generate_user_id();
    fputs($users_updated,$info_in['username'].';'.
        password_hash($info_in['password'],PASSWORD_DEFAULT).';'.
        get_timestamp().';'.
        $user_id.';'.
        PHP_EOL);
    fclose($users_updated);

    // create user dependencies
    mkdir('../../data/users/'.$user_id, 0755);
    file_put_contents('../data/users/'.$user_id.'/posts.json', json_encode([], JSON_PRETTY_PRINT));
    file_put_contents('../data/users/'.$user_id.'/portfolio.json', json_encode([], JSON_PRETTY_PRINT));
    
    header('Location: index.php'); // redirect back to index
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
            $users_existing[$row]['username']=$info_in['new_username'];
        }
    }
    // put column attributes, then write each line of users and close file
    fputcsv($users_updated,['username','password'],';');
    foreach ($users_existing as $fields){
        fwrite($users_updated,$fields['username'].';'.password_hash($fields['password'],PASSWORD_DEFAULT)."\n");
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
    fputcsv($users_updated,['username','password'],';');
    foreach ($users_existing as $fields){
        fwrite($users_updated,$fields['username']==$info_in['username']?"":implode(';',$fields)."\n");
        // delete user's directory
        
    }
    fclose($users_updated);
    header('Location: index.php'); //redirect back to index
    die;
}