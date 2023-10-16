<?php
require_once('../../lib/auth/auth.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../../lib/posts.php');
$portfolios=get_user_portfolio($userID);
$index=$_GET['index'];
?>
<h1 style="margin-bottom: 0px"><?=$portfolios[$index]['name']?></h1><br>
<h2>Category: <?=$portfolios[$index]['category']?></h2><br>
<?php
for($i=0;$i<count($portfolios[$index]['images']);$i++){
?>
<img src="../users/<?=$userID?>/images/<?=$portfolios[$index]['images'][$i]?>" style="max-width: 500px">
<?php
}?>
<br>
<a href="index.php"><< Back to index</a>