<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlogerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Available Bloggers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bloger-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'username',
            'email:email',
            'created_at',
            [
                'label' => 'Add Blogger',
                'format'=> 'raw',
                'value' => function($data, $id) use (&$communityId) {
                    return Html::button('Add', ['class'=>'btn btn-success','onclick'=>'addMember('.$id.','.$communityId.')']);
                }
            ]
        ],
    ]); 
            
 $this->registerJsFile('@web/js/j.js');  
 ?>
</div>
