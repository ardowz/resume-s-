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
        <a href="/basic/web/" class="navbar-brand" href="#">Resume <img height="17px" width="17px"src="/basic/web/img/apple-s.png"></a>
        <a href="#" id="navbar-aboutUs" class="pull-right">About Us</a>
    </nav>
</div>
    <!-- Page Content -->
    <div class="container">
    
        <!-- Introduction Row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">About Us</h1>
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
 