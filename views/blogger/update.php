<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bloger */

$this->title = 'Update Blogger: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bloggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bloger-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
