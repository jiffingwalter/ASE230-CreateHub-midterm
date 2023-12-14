<!DOCTYPE html>
<html lang="en">
<?php
require_once('../../lib/global.php');
$userID=isLoggedIn()?$_SESSION['userID']:'';
require_once($GLOBALS['userHandlingDirectory']);
require_once('../themes/head.php');

?>
<body id="page-top">
<?php
require_once('../themes/nav.php');
?>

        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <?= isLoggedIn($userID)?
                            '<h1 class="text-white font-weight-bold">Welcome back,<br>'.get_user($userID)['name'].'</h1>':
                            '<h1 class="text-white font-weight-bold">Welcome to CreateHub</h1>' // this should be a name eventually ?> 
                        <hr class="divider" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 mb-5">Where you can cupltivate your creativity, connect with like-minded individuals, and share your thoughts, images, and artistic creations.</p>
                        <a class="btn btn-primary btn-xl" href="#about">Find Out More</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <section class="page-section bg-primary" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">What is CreateHub?</h2>
                        <hr class="divider divider-light" />
                        <p class="text-white-75 mb-4">CreateHub is a social platform that allows artists and creators to upload posts as well as upload thier portfolios. Posts allow users to showcase something they've been working on, or to talk about whatever they want.</p>
                        <a class="btn btn-light btn-xl" href="../../lib/auth/login.php">Get Started!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container px-4 px-lg-5">
                <h2 class="text-center mt-0">At Your Service</h2>
                <hr class="divider" />
                <div class="row gx-4 gx-lg-5">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi-gem fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Create Posts</h3>
                            <p class="text-muted mb-0">Update people on a project you're working on, show off something new in your life, or simply talk about how your day went</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi-laptop fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Explore</h3>
                            <p class="text-muted mb-0">Go to the explore page now to see posts as well as other's portfolios</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi-globe fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Publish your Portfolio</h3>
                            <p class="text-muted mb-0">Upload your portfolio to your account to get recognized by other artists!</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi-heart fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Made with Love</h3>
                            <p class="text-muted mb-0">Created by: Bryce Bien & Justin Walter</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Made for ASE230 - Northern Kentucky University</div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>
