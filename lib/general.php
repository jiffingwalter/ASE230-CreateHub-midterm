<?php
require_once('auth/auth.php');

function display_error($context,$origin){
    echo "<h5 style='background-color: indianred; color: white; padding: 4px'>
            ERROR at ".$origin."<br>
            ".$context."
        </h5><br>";
}

function display_message($message){
    echo "<h5 style='background-color: lightskyblue; color: white; padding: 4px'>
            ".$message."
        </h5><br>";
}

function get_timestamp(){
    return date("m-d-y h:i:sa");
}