<?php
require_once('auth.php');
function createUser($email, $password){
    $fp=fopen('../../data/users/users.csv','a+');
    fputs($fp,$email.';'.password_hash($password,PASSWORD_DEFAULT).PHP_EOL);
    fclose($fp);
    //create user folder
    mkdir('../../data/users/'.getUserIndex($email), 0755);
    mkdir('../../data/users/'.getUserIndex($email).'/images', 0755);
    //create json for posts
    file_put_contents('../../data/users/'.getUserIndex($email).'/posts.json', json_encode([], JSON_PRETTY_PRINT));
    //create json for portfolio
    file_put_contents('../../data/users/'.getUserIndex($email).'/portfolio.json', json_encode([], JSON_PRETTY_PRINT));
}