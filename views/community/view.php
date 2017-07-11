<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Community */

$this->title = $model->name;
?>
<div class="community-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update Community', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',
            'onclick'=>'checkMemberStatus('.Yii::$app->user->id.','. $model->creator->id.',event )']) ?>
        <?= Html::a('Delete Community', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'onclick'=>'checkMemberStatus('.Yii::$app->user->id.','. $model->creator->id.',event )'
        ]) ?>
        <?= Html::a('Create Post in this Community', ['add-post', 'id' => $model->id], ['class' => 'btn btn-success',
            'id'=>'create-post-'.$model->id,
            'onclick'=>'isMember('.Yii::$app->user->id.','. $model->id.',event, this.id )'
            ]) ?>
        <?= Html::a('View Posts in this Community', ['group', 'id' => $model->id], ['class' => 'btn btn-default',
             'id'=>'view-post-'.$model->id,
            'onclick'=>'isMember('.Yii::$app->user->id.','. $model->id.',event, this.id )'
            ]) ?>
         <?= Html::a('Add members to Community', ['available-members', 'id' => $model->id], [
             'class' => 'btn btn-info', 
             'onclick'=>'checkMemberStatus('.Yii::$app->user->id.','. $model->creator->id.',event )'
             ]) ?>
    </p>
    
    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'created_by',
                'format' => 'text',
                'value' => $model->creator->name
            ],
            'total_posts',
            'created_at',
            'updated_at',
        ],
    ]);

 $this->registerJsFile('@web/js/j.js'); 
    ?>

</div>
