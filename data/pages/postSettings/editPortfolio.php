<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$portfolios=get_user_portfolio($userID);
?>
<a href="../portfolioSelect.php?index=<?=$index?>"><< BACK </a>

<?php
//add a way to add content to portfilio
?>

<h3> Click on a photo to delete </h3>
<?php
for($i=0;$i<count($portfolios[$index]['images']);$i++){
?>
<a href="./deletePortfolioPost.php?pIndex=<?=$i?>&index=<?=$index?>&name=<?=$portfolios[$index]['name']?>"><img src="../../users/<?=$userID?>/images/<?=$portfolios[$index]['images'][$i]?>" style="max-width: 500px"></a>
<?php
}?>