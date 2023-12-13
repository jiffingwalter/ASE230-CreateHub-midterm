<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once($GLOBALS['postHandlingDirectory']);
$index = $_GET['index'];
$posts=get_user_posts($userID);
$attachment=get_attachments($posts[$index]['pid']);

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
            <td><img class="card-img-top" style="height: 300px" src="<?php
                    // test for an attachment on the current post and show the image if it exists. show blank image otherwise
                    if($attachment=get_attachments($posts[$index]['pid'])){
                        echo '../users/'.$userID.'/images/'.$attachment[0]['file_name'];
                    }else{
                        echo '../users/No-image-found.jpg';
                    }
                    ?>"></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><?=$posts[$index]['title']?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?=$posts[$index]['content']?></td>
        </tr>
        <tr>
            <th>Date Created</th>
            <td><?=$posts[$index]['date_created']?></td>
        </tr>
        <!-- Add more rows as needed -->
    </table>
    <h1><a class="btn btn-primary" style="color: white" href="./postSettings/editPost.php?index=<?=$index?>">Edit Post</a></h1>
    <h1><a class="btn btn-primary" style="color: white" href="./postSettings/deletePost.php?index=<?=$index?>">Delete Post</a></h1>
    <a href="./userPage.php"><< BACK</a>
</body>