<?php
require_once($GLOBALS['generalDirectory']);

function readCSV($fileIn) {
    if (!file_exists($fileIn)){
        display_system_error('File not found at given location: '.$fileIn,$_SERVER['SCRIPT_NAME']);
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