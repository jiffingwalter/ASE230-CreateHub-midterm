<?php
require_once('../../lib/general.php');

function readCSV($fileIn) {
    if (!file_exists($fileIn)){
        display_system_error('Post data not found at given location: '.$fileIn,$_SERVER['SCRIPT_NAME']);
    }
    $fp=fopen($fileIn,'r');
    $get_csv=fgetcsv($fp,0,';');
    $data=[];

    while($content=fgetcsv($fp,0,';')){
        if(count($get_csv)===count($content)){
            $data[]=array_combine($get_csv,$content);
        }
    }
    fclose($fp);
    return $data;
}