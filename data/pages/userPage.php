<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:forceLogin();
require_once('../themes/head.php');
require_once('../themes/nav.php');
require_once($GLOBALS['postHandlingDirectory']);

$posts=get_user_posts($userID);

?>
<body id="page-top" style="background-color: black;">
    <div style="margin-top: 70px;"> <!--content-wrapper-->
        <h1><a class="btn btn-primary" style="color: white" href="./portfolio.php">Portfolio</a></h1>
        <h1><a class="btn btn-primary" style="color: white" href="createPost.php">Create a Post</a></h1>
        <table style="margin-left: auto; margin-right: auto">
            <?php
            for($i=0;$i<count($posts);$i++){?>
            <tr>
                <td>
                <div class="card" style="width: 400px;">
                    <img class="card-img-top" style="height: 300px" src="<?php
                    // test for an attachment on the current post and show the image if it exists. show blank image otherwise
                    if($attachment=get_attachments($posts[$i]['pid'])){
                        echo '../users/'.$userID.'/images/'.$attachment[0]['file_name'];
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
