<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);

$images=explode(',',$_POST['images'][0]);


if($_POST['yes']){
    for($i=0;$i<count($images);$i++){
        if(file_exists('../../users/'.$userID.'/images/'.$images[$i])){
            unlink('../../users/'.$userID.'/images/'.$images[$i]);
        }
    }
    //delete from db
    $deletePortfolio = db->preparedQuery('DELETE FROM portfolios WHERE fid = ?',[$_POST['fid']]);
}
header('Location: ../portfolio.php');