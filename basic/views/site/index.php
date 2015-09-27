<?php
use Yii\web\view;

/* @var $this yii\web\View */
    $this->title = 'Resume [s]';
    $this->registerCssFile("css/splashpage.css");
    $this->registerCssFile("https://fonts.googleapis.com/css?family=Noto+Sans");
    $this->registerCssFile("css/dropzone.css");
    $this->registerCssFile("css/animate.css");
    $this->registerJsFile("js/dropzone.js");
    $this->registerJsFile("js/splash.js");
    $this->registerJs("
    $(function() {
        Dropzone.autoDiscover = false;
        var FileUploadDropzone = new Dropzone('#dzForm', {
	        url: '/basic/web/api/upload',
	        paramName: 'uploadedFile',
	        dictDefaultMessage: '',
        });
    
        FileUploadDropzone.on('sending', function(file, xhr, formData) {
    						formData.append('candidateID', '". $candidateID ."');
    						});
    
        FileUploadDropzone.on('success', function(file, response) {
    						if (response == 'ERROR') {
    						alert('There was an error processing your file. Please try again.');
    						}
    						});
      });
    ",View::POS_HEAD,"dropzone");
?>

<script type="text/javascript">
    
</script>
<div class="container">
    <nav class="navbar navbar-fixed-top navbar-light">
        <a class="navbar-brand" href="#">Resume <img height="17px" width="17px"src="img/apple-s.png"></a>
        <a href="site/about" id="navbar-aboutUs" class="pull-right">About Us</a>
    </nav>
</div>
<section id="splash-landing">
    <div class="container-fluid bigLandingPic">
        <div class="row positive-message">
            <div class="col-sm-12 text-center">
                <div class="text">Bringing Resumes into the future</div>
            </div>
        </div>
        <div class="row main-buttons text-center">
            <div class="col-sm-12">
                <button id="upload" class="btn btn-upload" type="submit">UPLOAD</button>
                <button id="search" class="btn btn-search" type="submit">SEARCH</button>
            </div>
        </div>
    </div>    
</section>

<section id="splash-upload" class="hidden">
    <div class="container">
        <div class="row">
            
            <div class="col-sm-9 ">
                <form action="/basic/web/api/upload" method="post">
                    <div class="dropzone" id="dzForm"></div>
                </form>
                
            </div>
            <div class="col-sm-3">
                <input type="button" id="newCandidate" class="btn btn-next addNewCandidateBtn" value="Next Candidate"><span class="glyphicon glyphicon-plus"></span></input>
            </div>
        </div>
    </div>
</section>


<section id="splash-search" class="hidden">
    <div class="container">    
        <div class="row">
            <div class="col-sm-12">
                <form id="search-form">
                   <div class="input-group">
                      <input name="query" type="text" class="form-control" placeholder="Find Applicants with these talents...">
                      <span class="input-group-btn">
                        <button class="btn btn-query" type="submit">Search</button>
                      </span>
                    </div><!-- /input-group -->
                </form>
            </div>
        </div>
        
        <div class="row">
            <div id="search-results" class="col-sm-12"></div>
        </div>
    </div>
</section>