<?php


require_once('../themes/head.php');

?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="./index.php">Create Hub</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <?= isUserAdmin($userID)?'<li class="nav-item"><a class="nav-link" href="../../admin/index.php">Admin Dashboard</a></li>':''?>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/userPage.php">My Page</a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/portfolio.php">My Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/explore.php">Explore</a></li>
                <?= isLoggedIn($userID)?'<li class="nav-item"><a class="nav-link" href="../../lib/auth/logout.php">Logout</a></li>':
                    '<li class="nav-item"><a class="nav-link" href="../../lib/auth/login.php">Sign up/Login</a></li>'?>
            </ul>
        </div>
    </div>
</nav>