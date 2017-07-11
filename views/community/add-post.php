<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = 'Create Post in Community';
$this->params['breadcrumbs'][] = ['label' => 'Communities', 'url' => 'list'];
$this->params['breadcrumbs'][] = $this->title;
?>
  <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo "<div class='alert alert-danger'>" . $message . "</div>";
    }
    ?>
<div class="post-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_post-form', [
        'model' => $model,
    ]) ?>

</div>
