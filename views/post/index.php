<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All Posts on Socialblog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
   <?php echo ( (!Yii::$app->user->isGuest) ? Nav::widget([
    'activateItems'=> true,
    'options'=>[
        'class'=>'nav nav-pills nav-justified'
    ],
    'items'=>[
         [
            'label'=> 'Create Post',
             'url'=> 'create'
        ],
         [
            'label'=> 'My Profile',
             'url'=> ['profile','id'=> Yii::$app->user->id]
        ],
    ]
]) : "")?>
    
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo "<div class='alert alert-danger'>" . $message . "</div>";
    }
    ?>
 
    <?php  
    if (!empty($posts)) {
    foreach ($posts as $post) {
        echo $this->render('post-template', ['post' => $post]);
    }
}
    ?>
</div>
