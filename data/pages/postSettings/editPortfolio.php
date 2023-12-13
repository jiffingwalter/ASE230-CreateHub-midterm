<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$portfolios=get_user_portfolio($userID);

if(count($_POST)>0){
    if($_POST['title'] != ''){
        $changeTitle = db->preparedQuery('UPDATE portfolios SET name = ? WHERE fid = ?',[$_POST['title'],$portfolios[$index]['fid']]);
        header('Location: ../portfolio.php');
    }

    if($_POST['category'] != ''){
        $changeTitle = db->preparedQuery('UPDATE portfolios SET category = ? WHERE fid = ?',[$_POST['category'],$portfolios[$index]['fid']]);
        header('Location: ../portfolio.php');
    }
}
?>
<a href="../portfolioSelect.php?index=<?=$index?>"><< BACK </a>

<?php
//add a way to add content to portfilio
?>
<form method="POST" action="editPortfolio.php?index=<?=$index?>">
    <h3>Title: <?=$portfolios[$index]['name']?></h3>
    <input type="text" name="title">
    <input type="submit" value="Change Title">
</form>

<form method="POST" action="editPortfolio.php?index=<?=$index?>">
    <h3>Category: <?=$portfolios[$index]['category']?></h3>
    <input type="text" name="category">
    <input type="submit" value="Change Category">

</form>

<h3> Click on a photo to delete </h3>
<?php
for($i=0;$i<count($portfolios[$index]['images']);$i++){
?>
<a href="./deletePortfolioPost.php?pIndex=<?=$i?>&index=<?=$index?>&name=<?=$portfolios[$index]['name']?>"><img src="../../users/<?=$userID?>/images/<?=$portfolios[$index]['images'][$i]?>" style="max-width: 500px"></a>
<?php
}?>