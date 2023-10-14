<?php
require_once('../../scripts/readCSV.php');

function get_all_users(){
    return readCSV('../../data/users/users.csv');
}

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
    // append new user to end of user data file
    $users_updated=fopen('../../data/users/users.csv','a');
    fputs($users_updated,$info_in['username'].';'.password_hash($info_in['password'],PASSWORD_DEFAULT).PHP_EOL);
    fclose($users_updated);

    // get the new users index
    $u_index=getUserIndex($info_in['username']);

    // create user dependencies
    mkdir('../../data/users/'.$u_index, 0755);
    file_put_contents('../../data/users/'.$u_index.'/posts.json', json_encode([], JSON_PRETTY_PRINT));
    file_put_contents('../../data/users/'.$u_index.'/portfolio.json', json_encode([], JSON_PRETTY_PRINT));
    
    header('Location: index.php'); // redirect back to index
}

function edit_users($info_in){
    echo 'Saving changes to user '.$info_in['id'].' ...';
    $users_existing=get_all_users();
    $users_updated=fopen('../../data/users/users.csv','w');

    // step through existing users, update infomation if the (old) username matches
    for ($row=0;$row < count($users_existing);$row++){
        if ($users_existing[$row]['username'] == $info_in['last_username']){
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
    $users_updated=fopen('../../data/users/users.csv','w');

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