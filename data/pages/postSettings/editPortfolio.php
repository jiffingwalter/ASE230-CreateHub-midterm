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
        // echo '<pre>';
        // print_r($addPhotos);
        // echo '</pre>';

        // echo '<pre>';
        // print_r($_FILES['images']['full_path']);
        // echo '</pre>';

        //add locally
        for($i=0;$i<count($_FILES['images']['full_path']);$i++){
            move_uploaded_file($_FILES['images']['tmp_name'][$i], '../../users/'.$userID.'/images/'.$_FILES['images']['full_path'][$i]);
        }

        //implode on , and update
        $updatePhotos = db->preparedQuery('UPDATE portfolios SET images = ? WHERE fid = ?',[implode(',',$addPhotos),$portfolios[$index]['fid']]);
    }else{
        echo '<h1> FILES EMPTY </h1>';
    }
    header('Location: ../portfolio.php');
    //print_r($_FILES['images']);
}
?>
<a href="../portfolioSelect.php?index=<?=$index?>"><< BACK </a>

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

<h3> Click on a photo to delete </h3>
<?php
for($i=0;$i<count($portfolios[$index]['images']);$i++){
?>
<a href="./deletePortfolioPost.php?pIndex=<?=$i?>&index=<?=$index?>&name=<?=$portfolios[$index]['name']?>"><img src="../../users/<?=$userID?>/images/<?=$portfolios[$index]['images'][$i]?>" style="max-width: 500px"></a>
<?php
}?>