<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
?>
<body id="page-top" style="background-color: black; margin-top: 100px">
    <?php
    require_once('../themes/nav.php');
    ?>
    <a class="btn btn-primary" style="color: white" href="./createPortfolio.php">Create a new portfolio</a>
<!-- Portfolio-->
<div id="portfolio" style="margin-top: 70px;">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/1.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/1.jpg" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
</body>