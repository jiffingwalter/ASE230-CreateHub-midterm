<?php
require_once('../../lib/auth/auth.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once('../../scripts/readJSON.php');
$posts=readJSON('../users/'.$userID.'/posts.json');
$index = $_GET['index'];
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
            <td><img src="<?php
                    if($posts[$index]['attachments']['error'] != 'noFileUploaded'){
                        echo '../users/'.$userID.'/images/'.$posts[$index]['attachments']['name'];
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
    <a href="./userPage.php"><< BACK</a>
</body>