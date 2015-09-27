<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    $authAuthChoice = AuthChoice::begin([
        'baseAuthUrl' => ['site/auth']
    ]); 
    
    foreach ($authAuthChoice->getClients() as $client): 
    ?>
        <p><?php $authAuthChoice->clientLink($client) ?></p>
    <?php 
    endforeach; 
    
    AuthChoice::end(); 
    ?>
</div>
