<?php
require_once('../../../lib/global.php');
require_once('../../themes/head.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$portfolios=get_user_portfolio($userID);
$images=[];

//set images in the portfolio
for($i=0;$i<count($portfolios[$index]['images']);$i++){
    $images[] = $portfolios[$index]['images'][$i];
}

if(count($_POST)>0){
    if($_POST['title'] != ''){
        $changeTitle = db->preparedQuery('UPDATE portfolios SET name = ? WHERE fid = ?',[$_POST['title'],$portfolios[$index]['fid']]);
    }

    if($_POST['category'] != ''){
        $changeTitle = db->preparedQuery('UPDATE portfolios SET category = ? WHERE fid = ?',[$_POST['category'],$portfolios[$index]['fid']]);
    }

    if($_FILES['images'] != 0){
        echo '<h1> FILES NOT EMPTY </h1>';
        //query photos
        $addPhotos = db->preparedQuery('SELECT images FROM portfolios WHERE fid = ?',[$portfolios[$index]['fid']]);

        //explode photos on ,
        $addPhotos = explode(',',$addPhotos['images']);

        //append the new uploads
        for($i=0;$i<count($_FILES['images']['full_path']);$i++){
            $addPhotos[] = $_FILES['images']['full_path'][$i];
        }

        //add locally
        for($i=0;$i<count($_FILES['images']['full_path']);$i++){
            move_uploaded_file($_FILES['images']['tmp_name'][$i], '../../users/'.$userID.'/images/'.$_FILES['images']['full_path'][$i]);
        }

        //implode on , and update
        $updatePhotos = db->preparedQuery('UPDATE portfolios SET images = ? WHERE fid = ?',[implode(',',$addPhotos),$portfolios[$index]['fid']]);
    }
    header('Location: ../portfolio.php');
}
?>
<link href="../../../dist/css/styles.css" rel="stylesheet" />

<nav>
    <a class="btn btn-primary" style="color: white" href="../portfolioSelect.php?index=<?=$index?>">Back</a><br><br>
</nav>

<body style="background-color: black; color: white;">
<form method="POST" enctype="multipart/form-data" action="editPortfolio.php?index=<?=$index?>">

    <h3>Title: <?=$portfolios[$index]['name']?></h3>
    <input type="text" name="title">

    <h3>Category: <?=$portfolios[$index]['category']?></h3>
    <input type="text" name="category">
    
    
    <h3>Add photo(s):</h3>
    <label for="images">Upload Images</label><br>
    <input type="file" name="images[]" accept=".png, .jpg, .jpeg" multiple><br><br>
    <input type="hidden" name="user_id" value="<?=$userID?>">
    <input type="submit" value="Upload Changes">
</form>

<form method="POST" action="deletePortfolio.php">
    <h3>Delete Portfolio?</h3>
    <input type="text" name="images[]" value=<?=implode(',',$images)?> hidden>
    <input type="text" name="fid" value=<?=$portfolios[$index]['fid']?> hidden>
    <input type="submit" value="delete portfolio">
</form>

<h3> Click on a photo to delete </h3>
<?php
for($i=0;$i<count($portfolios[$index]['images']);$i++){
?>
<a href="./deletePortfolioPost.php?pIndex=<?=$i?>&index=<?=$index?>&name=<?=$portfolios[$index]['name']?>&image=<?=$portfolios[$index]['images'][$i]?>"><img src="../../users/<?=$userID?>/images/<?=$portfolios[$index]['images'][$i]?>" style="max-width: 500px"></a>
<?php
}?>
</body>