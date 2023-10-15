<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
require_once('../../scripts/readJSON.php');
require_once('../themes/nav.php');
?>
<body id="page-top" style="background-color: black;">
    <div style="margin-top: 70px;"> <!--content-wrapper-->
        <h1><a class="btn btn-primary" style="color: white" href="./portfolio.php<?=$index?>">Portfolio</a></h1>
        <h1><a class="btn btn-primary" style="color: white" href="createPost.php">Create a Post</a></h1>
        <table style="margin-left: auto; margin-right: auto">
        <tr>
            <?php
            $posts=readJSON('../users/'.$userID.'/posts.json');
            for($i=0;$i<count($posts);$i++){?>
            <td>
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" style="height: 300px" src="
                    <?php
                    if($posts[$i]['3']['error'] != 'noFileUploaded'){
                        echo '../users/'.$userID.'/images/'.$posts[$i]['3']['full_path'];
                    }else{
                        echo '../users/No-image-found.jpg';
                    }
                    ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?=$posts[$i]['title']?></h5>
                        <p class="card-text"><?=$posts[$i]['content']?></p>
                        <h5 class="card-text"><?=$posts[$i]['date_created']?></h5>
                        <a href="post.php?index=<?=$i?>" class="btn btn-primary">View Post</a>
                    </div>
                </div>
            </td>
            <?php
            }?>
        </tr>
        </table>
    </div> <!--content-wrapper-->
</body>
