<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to My Social Network</h1>

         <p><a <?= Html::a('Go to main post page', ['post/index'], ['class'=>'btn btn-lrg btn-success'])?></p>
    </div>

    <div class="body-content">
        
    </div>
</div>
