<?php
require_once('../../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once($GLOBALS['databaseDirectory']);
$index = $_GET['index'];
$portfolio = db->preparedQuery('SELECT * FROM portfolios WHERE name = ? AND author = ?',[$_GET['name'],$userID]);
$images = explode(',',$portfolio['images']);

if(isset($_POST['yes'])){
    if($_POST['yes']){
        //delete portfolio image
        unset($images[$_GET['pIndex']]);
        $setImages = db->preparedQuery('UPDATE portfolios SET images = ? WHERE fid = ?',[implode(',',$images),$portfolio['fid']]);
        header("Location: ./editPortfolio.php?index=$index");
    }
}
?>

<body id="page-top" style="background-color: black; color: white;">
    <div style="margin-top: 100px; text-align: center">
    <h1>Are you sure you want to delete this photo? This cannot be undone.</h1><br><br>
        <form method="POST">
            <label for="yes">YES</label>
            <input type="checkbox" name="yes" id="yes"><br><br>
            <input type="submit">
            <br><br>
        </form>
        <a href="./editPortfolio.php?index=<?=$index?>"><< BACK TO PORTFOLIO</a>
        <br><br><br>
    </div>
</body>
