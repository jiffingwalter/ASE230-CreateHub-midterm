<?php
require_once('../../lib/global.php');
require_once('../themes/head.php');
$userID=isLoggedIn()?$_SESSION['userID']:$_SESSION['userID'] = 'guest';
require_once('../themes/nav.php');
require_once($GLOBALS['postHandlingDirectory']);

$portfolios=get_all_portfolios();
?>

<body style="background-color: black; margin-top: 70px">
<table style="margin-left: auto; margin-right: auto;">
<?php
foreach($portfolios as $portfolio){
    $index = key($portfolios);
    $image = explode(',', $portfolio['images']);
?>
    <tr>
        <td>
        <div id="portfolio" style="margin-top: 10px;">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="./viewPortfolio.php?index=<?=$index?>" title="Project Name">
                        <img style="max-width: 500px" class="img-fluid" src="../users/<?=$portfolio['author']?>/images/<?=$image[0]?>" alt="..." />
                        <div class="portfolio-box-caption" style="width: 500;">
                            <div class="project-category text-white-50"><?=$portfolios[$index]['category']?></div>
                            <div class="project-name"><?=$portfolios[$index]['name']?></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
        </td>
    </tr>
<?php
    next($portfolios);
}
?>
</body>