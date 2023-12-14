<?php
require_once('../../lib/global.php');
require_once('../themes/head.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/nav.php');
require_once($GLOBALS['postHandlingDirectory']);
$portfolios=get_all_portfolios();
$index=$_GET['index'];

$portfolio=$portfolios[$index];
$images=explode(',',$portfolio['images']);
?>

<a href="explorePortfolios.php" class="btn btn-primary"><< Back</a><br><br>
<?php
if($portfolio['author'] == $userID){
?>
    <a href="portfolio.php" class="btn btn-primary">View on your page</a><br><br>
<?php
}?>


<body style="margin-top: 150px; background-color: black; color: white;">
<?php
    for($i=0;$i<count($images);$i++){
?>
        <img src="../users/<?=$portfolio['author']?>/images/<?=$images[$i]?>" style="max-width: 500px">
<?php
}?>
</body>
