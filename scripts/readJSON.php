<?php
require_once('../../lib/general.php');

function readJSON($fileIn) {
    if (file_exists($fileIn)){
        $json_file=file_get_contents($fileIn);
        return json_decode($json_file,true);
    } else {
        display_error('Post data not found at given location: '.$fileIn,$_SERVER['SCRIPT_NAME']);
    }
}