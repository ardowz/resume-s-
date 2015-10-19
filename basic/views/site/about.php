<?php
use Yii\web\view;

/* @var $this yii\web\View */
$this->title = 'Resume [s] - About Us';
$this->registerCssFile("css/aboutus.css");
$this->registerCssFile("https://fonts.googleapis.com/css?family=Noto+Sans");
$this->registerCssFile("css/animate.css");
?>
<a href="https://github.com/ardowz/resume-s-"><img style="position: absolute; z-index:2; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/a6677b08c955af8400f44c6298f40e7d19cc5b2d/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677261795f3664366436642e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png"></a>
<div class="container">
    <nav class="navbar navbar-fixed-top navbar-light z0">
        <a href="/" class="navbar-brand" href="#">Resume <img height="17px" width="17px"src="img/apple-s.png"></a>
    </nav>
</div>
<!-- Page Content -->
<div class="container">

    <!-- Introduction Row -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">About</h1>
            <h2>What is this?</h2>
            <p>This is a quick project thrown together by friends and coworkers for the Integrate 2015 Hackathon. Our code is a little messy at places due to a very tight time constraint, and there may be a bug or two, but we are all really proud to have produced such a fun product in such little time! </p>
            <h2>About the Project</h2>
            <p>Our product allows recruiters to upload photos which contains the candidate's resume and face, to our app. We start by uploading files from the recruiters computer or phone using <b>HP's Object Store API</b>. We use <b>HP's optical character recognition API</b> and the <b>facial recognition API</b> through object references to save the text from the resume and the image of candidates face. After which we allow recruiters to search, using <b>HP's query index API</b>, for potential candidates based on particular skill or experience and we show the result of candidates who have mentioned the specific skills in their resumes.</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Technology being used</h1>
            <h3 href="https://www.idolondemand.com/developer/apis">HP Idol OnDemand</h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Future Implementations</h1>
            <ul>
                <li>Using the <b>speech-to-text API</b> to connvert the conversation between recruiter and candidate.</li>
                <li>Applying <b>sentiment analysis</b> on the converted text to measure how positive the candidate is.</li>
            </ul>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-lg-12">
            <h2>GitHub</h2>
            Check out the source at <a href="https://github.com/ardowz/resume-s-">https://github.com/ardowz/resume-s-</a>
        </div>
    </div>
    <hr>

    <!-- Team Members Row -->
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Our Team</h2>
        </div>
        <div class="col-lg-3 col-sm-6 text-center">
            <img class="img-circle img-responsive img-center" src="img/celso.png" alt="">
            <h3 class="celso">Celso Hiroshi Endo</h3>
            <small>Developer</small></br>
            <a href="mailto:celso@celsoendo.com">celso@celsoendo.com</a>
        </div>
        <div class="col-lg-3 col-sm-6 text-center">
            <img class="img-circle img-responsive img-center" src="img/phil.png" alt="">
            <h3>Philip Reasa</h3>
            <small>Developer</small></br>
            <a href="mailto:philipreasa@gmail.com">philipreasa@gmail.com</a>
        </div>
        <div class="col-lg-3 col-sm-6 text-center">
            <img class="img-circle img-responsive img-center" src="img/bernardo.png" alt="">
            <h3>Bernardo Avancena</h3>
            <small>Developer</small></br>
            <a href="mailto:ardowz@gmail.com">ardowz@gmail.com</a>
        </div>
        <div class="col-lg-3 col-sm-6 text-center">
            <img class="img-circle img-responsive img-center" src="img/sritam.png" alt="">
            <h3>Sritam Patnaik</h3>
            <small>Developer</small></br>
            <a href="mailto:sritampatnaik@gmail.com">sritampatnaik@gmail.com</a>
        </div>
    </div>

</div>
 