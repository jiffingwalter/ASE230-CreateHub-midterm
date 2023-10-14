<?php
require_once('auth/auth.php');

function display_system_error($context,$origin){
    echo "<h4 style='background-color: indianred; color: white; padding: 6px'>
            ERROR at ".$origin."<br>
            ".$context."
        </h4>";
}

function display_error($message){
    echo "<p style='background-color: indianred; color: white; padding: 6px'>
            ".$message."
        </p>";
}

function display_message($message){
    echo "<p style='background-color: lightskyblue; color: white; padding: 6px'>
            ".$message."
        </p>";
}

function get_timestamp(){
    return date("m-d-y h:i:sa");
}