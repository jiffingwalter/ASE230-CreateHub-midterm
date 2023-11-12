<?php
// multi-purpose functions

// display system errors (things unpreventable by user)
function display_system_error($context,$origin){
    echo "<h4 style='background-color: darkred; color: white; padding: 6px'>
            SYSTEM ERROR at ".$origin."<br>
            ".$context."
        </h4>";
}

// display error (something probably caused by user's input, etc)
function display_error($message){
    echo "<p style='background-color: indianred; color: white; padding: 6px'>
            ".$message."
        </p>";
}

// display generic message (login success messages, etc)
function display_message($message){
    echo "<p style='background-color: lightskyblue; color: white; padding: 6px'>
            ".$message."
        </p>";
}

// file extention array for images
function get_file_extensions(){
    return ['png', 'jpg', 'jpeg'];
}

// generates a timestamp
function get_timestamp(){
    return date("m/d/y h:i:sa");
}

// gets an array of files in a directory
function get_directory_contents($directory){
    $files=scandir($directory);
    array_splice($files,0,2);
    return $files;
}