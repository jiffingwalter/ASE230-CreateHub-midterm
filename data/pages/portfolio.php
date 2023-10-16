<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
require_once('../../lib/posts.php');
$portfolios=get_user_portfolio($userID);
?>
<body id="page-top" style="background-color: black; margin-top: 100px">
    <?php
    require_once('../themes/nav.php');
    ?>
    <a class="btn btn-primary" style="color: white" href="./createPortfolio.php">Create a new portfolio</a>
<!-- Portfolio-->
<?php
for($i=0;$i<count($portfolios);$i++){
?>
<div id="portfolio" style="margin-top: 70px;">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="portfolioSelect.php?index=<?=$i?>" title="Project Name">
                    <img style="max-width: 500px" class="img-fluid" src="../users/<?=$userID?>/images/<?=$portfolios[$i]['images'][0]?>" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50"><?=$portfolios[$i]['category']?></div>
                        <div class="project-name"><?=$portfolios[$i]['name']?></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
}?>
</body>