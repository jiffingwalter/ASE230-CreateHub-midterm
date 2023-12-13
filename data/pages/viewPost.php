<?php
require_once('../../lib/global.php');
require_once('../themes/head.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/nav.php');
require_once($GLOBALS['postHandlingDirectory']);
$posts=get_all_posts();
$index=$_GET['index'];
$upload=$posts[$index];
$attachment=get_attachments($upload['pid']);
?>
<style>
    .table{
        width: 100%;
        border-collapse: collapse;
        color: white;
    }
    .table th {
        background-color: #f2f2f2;
        text-align: center;
        padding: 10px;
        color: black;
    }
    .table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .table img{
        max-width: 600px;
    }
</style>
<body style="margin-top: 150px; background-color: black; color: white;">

    <table class="table">
        <tr>
            <th>Image</th>
            <td><img class="card-img-top" style="height: 300px" src=<?php
                    // test for an attachment on the current post and show the image if it exists. show blank image otherwise
                    if($attachment=get_attachments($upload['pid'])){
                        echo '../users/'.$upload['author'].'/images/'.$attachment[0]['file_name'];
                    }else{
                        echo '../users/No-image-found.jpg';
                    }
                    ?>></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><?=$upload['title']?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?=$upload['content']?></td>
        </tr>
        <tr>
            <th>Date Created</th>
            <td><?=$upload['date_created']?></td>
        </tr>
        <!-- Add more rows as needed -->
    </table>
    <?php
    if($userID == $upload['author']){
    ?>
    <h1><a class="btn btn-primary" style="color: white" href="./userPage.php">View on your profile</a></h1>
    <?php
    }?>
    <a href="./explore.php"><< BACK</a>
</body>
