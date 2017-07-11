<?php

use yii\helpers\Html;
use app\models\Blogger;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['profile','id'=>$model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
$photoInfo = $model->PictureInfo;
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo "<div class='alert alert-danger'>" . $message . "</div>";
    }   
?>
<div class="post-view">
    <p>
        <?= ((!Yii::$app->user->isGuest && Yii::$app->user->id == $model->user_id) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '') ?>
        <?= ((!Yii::$app->user->isGuest && Yii::$app->user->id == $model->user_id) ? Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '') ?>
    </p>
    
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
            <h1 align="center"><?= Html::encode($this->title) ?> </h1>           
            <?= Html::img($photoInfo['url'],['class'=>'img-responsive', 'alt'=> $photoInfo['alt']]) ?>
            <p class="text-responsive"><?= $model->text?></p>
            <h4><?php echo 'Post created at '. $model->created_at ?></h4>
            <?= Html::button('Add Comment',['class'=>'btn btn-success','onclick'=>'openCommentForm('.$model->id.')']) ?>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <h3>Comments :</h3>
            <h4> <?= $model->total_comments ?> Total Comments</h4>           
         <?php 
         foreach($model->comments as $comment){            
             echo $this->render('comment-section',['comment'=> $comment]);
         }
         ?>
        </div>
    </div>

</div>

<?php 
Modal::begin(['header'=> '<h4>Add Comment User : '.(Yii::$app->user->isGuest ? 'Anonymous' : Blogger::findOne(['id'=>Yii::$app->user->id])->name ).'</h4>',
    'id'=> 'modal-'.$model->id,
    'size'=>'modal-md']);
echo "<div id=modalContent-".$model->id."></div>";
Modal::end();
$this->registerJsFile('@web/js/j.js'); 
?>

