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
                    <img class="card-img-top" src="<?php
                        if($posts[$i]['3']['name'] != 'noFileUploaded'){
                            echo '../users/'.$userID.'/images/'.$i.'.'.$posts[$i]['3']['6'];
                        }else{
                            echo '../users/No-image-found.jpg';
                        }
                    ?>" style="height: 300px">
                    <div class="card-body">
                        <h5 class="card-title"><?=$posts[$i]['title']?></h5>
                        <p class="card-text"><?=$posts[$i]['content']?></p>
                        <h5 class="card-text"><?=$posts[$i]['date_created']?></h5>
                        <a href="post.php?index=<?=$i?>&ext=<?=$posts[$i]['3']['6']?>" class="btn btn-primary">View Post</a>
                    </div>
                </div>
            </td>
            <?php
            }?>
        </tr>
        </table>
    </div> <!--content-wrapper-->
</body>
