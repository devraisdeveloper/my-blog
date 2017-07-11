<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Community */

$this->title = 'Create Community';
$this->params['breadcrumbs'][] = ['label' => 'Communities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

 <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo "<div class='alert alert-danger'>" . $message . "</div>";
    }
    ?>
<div class="community-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
