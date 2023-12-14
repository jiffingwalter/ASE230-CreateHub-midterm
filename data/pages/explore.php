<?php
require_once('../../lib/global.php');
require_once('../themes/head.php');
$userID=isLoggedIn()?$_SESSION['userID']:$_SESSION['userID'] = 'guest';
require_once('../themes/nav.php');

require_once($GLOBALS['postHandlingDirectory']);
$posts=get_all_posts();
?>
<body style="background-color: black; margin-top: 70px">
<a class="btn btn-primary" style="color: white" href="./explorePortfolios.php">Explore Portfolios</a>
<table style="margin-left: auto; margin-right: auto;">
<?php
foreach($posts as $upload){
    $index = key($posts);
?>
    <tr>
        <td>
            <div class="card" style="width: 500px;">
                <img class="card-img-top" style="height: 400px;" src=<?php
                if($attachment=get_attachments($upload['pid'])){
                    echo '../users/'.$upload['author'].'/images/'.get_attachment_photo($upload['pid']);
                }else{
                    echo '../users/No-image-found.jpg';
                }
                ?>>
                <div class="card-body">
                    <h5 class="card-title"><?=$upload['title']?></h5>
                    <p class="card-text"><?=$upload['content']?></p>
                    <h5 class="card-text"><?=$upload['date_created']?></h5>
                    <a href="viewPost.php?index=<?=$index?>" class="btn btn-primary">View Post</a>
                </div>
            </div>
        </td>
    </tr>
    <?php
    next($posts);
}?>
</table>
</body>