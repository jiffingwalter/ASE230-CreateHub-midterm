<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
?>
<body id="page-top" style="background-color: black;">
    <?php
    require_once('../themes/nav.php');
    ?>
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
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/2.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/2.jpg" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/3.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/3.jpg" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/4.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/4.jpg" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/5.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/5.jpg" alt="..." />
                    <div class="portfolio-box-caption">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a class="portfolio-box" href="../../dist/assets/img/portfolio/fullsize/6.jpg" title="Project Name">
                    <img class="img-fluid" src="../../dist/assets/img/portfolio/thumbnails/6.jpg" alt="..." />
                    <div class="portfolio-box-caption p-3">
                        <div class="project-category text-white-50">Category</div>
                        <div class="project-name">Project Name</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
</body>