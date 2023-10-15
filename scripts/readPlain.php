<?php
function readPlain($fileIn) {
    if (file_exists($fileIn)){
        fopen($fileIn,'r');
        return file_get_contents($fileIn);
        fclose($fileIn);
    } else {
        display_system_error('File not found at given location: '.$fileIn,$_SERVER['SCRIPT_NAME']);
    }
}