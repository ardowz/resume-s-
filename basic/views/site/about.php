<?php
use Yii\web\view;

/* @var $this yii\web\View */
    $this->title = 'Resume [s] - About Us';
    $this->registerCssFile("/basic/web/css/aboutus.css");
    $this->registerCssFile("https://fonts.googleapis.com/css?family=Noto+Sans");
    $this->registerCssFile("css/animate.css");
?>
<div class="container">
    <nav class="navbar navbar-fixed-top navbar-light">
        <a href="#" class="navbar-brand" href="#">Resume <img height="17px" width="17px"src="/basic/web/img/apple-s.png"></a>
        <a href="site/about" id="navbar-aboutUs" class="pull-right">About Us</a>
    </nav>
</div>
    <!-- Page Content -->
    <div class="container">
    
        <!-- Introduction Row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">About Us</h1>
                <p>Allows recruiters to take photo of resumes with the candidates face or without. We can the resume for the face and the text in the resume and store them. After which we the recruiters can search for candidates with specific skills and experiences and view their resume together with their faces.</p>
            </div>
        </div>

        <!-- Team Members Row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Our Team</h2>
            </div>
            <div class="col-lg-3 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="/basic/web/img/celso.png" alt="">
                <h3 class="celso">Celso Hiroshi Endo</h3>
                <small>Developer</small>
            </div>
            <div class="col-lg-3 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="/basic/web/img/phil.png" alt="">
                <h3>Philip Reasa</h3>
                <small>Developer</small>
            </div>
            <div class="col-lg-3 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="/basic/web/img/bernardo.png" alt=""> 
                <h3>Bernardo Avancena</h3>
                <small>Developer</small>
            </div>
            <div class="col-lg-3 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="/basic/web/img/sritam.png" alt="">
                <h3>Sritam Patnaik</h3>
                <small>Developer</small>
            </div>
        </div>

    </div>
 