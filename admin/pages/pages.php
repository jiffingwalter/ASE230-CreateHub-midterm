<?php
session_start();
$userID=$_SESSION['userID'];
require_once('../../lib/general.php');

function get_pages(){
    $pages=scandir('../../data/pages');
    array_splice($pages,0,2);
    return $pages; //returns array of pages
}

function create_page($page){
    if(!file_exists('../../data/page/'.$page['name'])){
        file_put_contents('../../data/pages/'.$page['name'],$page['code']);
        display_message('Page has been created at (root)/data/pages/'.$page['name']);
    ?>
        <a href="../../data/pages/<?=$page['name']?>">Go to <?=$page['name']?></a>
    <?php
    }else{
        display_error('This page already exists.');
    ?>
        <a href='index.php'><< Back</a>
    <?php
        die();
    }
}

function edit_page($newPage){
    $oldPage='../../data/pages/'.$newPage['name'];
    file_put_contents($oldPage, $newPage['code']);
}

function delete_page($page){
    $pageToDelete=$page['delete_name'];
    unlink('../../data/pages/'.$pageToDelete);
}
?>