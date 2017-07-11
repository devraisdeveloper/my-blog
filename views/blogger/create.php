<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bloger */

$this->title = 'Create Bloger';
$this->params['breadcrumbs'][] = ['label' => 'Blogers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bloger-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
