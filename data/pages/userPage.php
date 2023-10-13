<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
require_once('../../scripts/readJSON.php');
?>

<body id="page-top" style="background-color: black;">
    <?php
    require_once('../themes/nav.php');
    ?>
    <div style="margin-top: 70px;"> <!--content-wrapper-->
        <h1><a class="nav-link" style="color: white" href="./portfolio.php<?=$index?>">Portfolio</a></h1>
        <table style="margin-left: auto; margin-right: auto">
        <tr>
            <?php
            $posts=readJSON('../users/user_posts.json');
            for($i=0;$i<count($posts);$i++){?>
            <td>
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="..." alt="Post Title">
                    <div class="card-body">
                        <h5 class="card-title"><?=$posts[$i]['title']?></h5>
                        <p class="card-text"><ul>
                            <?php
                            foreach($posts[$i]['tags'] as $tag){?>
                                <li><?=$tag?></li>
                            <?php
                            }?>
                        </ul></p>
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
