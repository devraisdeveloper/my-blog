<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\View;
use app\models\Blogger;

$photoInfo = $post->PictureInfo;
$photo = Html::img($photoInfo['url'],['alt' => $photoInfo['alt'], 'class' => "bg-primary"]); 
$options = ['data-lightbox'=> 'profile-image', 'data-tittle'];
$currentUser = Yii::$app->user->id;
?>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    <div class="thumbnail">
        <?=  Html::a($photo,$photoInfo['url'],$options);?>
        <div class="caption">
            <h4><?=  Html::a($post->title,'/socialblog/web/post/view?id='.$post->id) ?></h4>
            <p> <?= $post->text ?> </p>
            <div class="comment">                            
                 <?= (Yii::$app->user->id != $post->user_id ) ? Html::tag('p', Html::encode('Add comment'), ['align' => "right", 'onclick' => 'openCommentForm(' . $post->id . ')']) : '' ?>
            </div>
            <div class="row text-right bg-info">
                <div class="col-xs-6">
                    <span id="comment-<?= $post->id?>" class="glyphicon glyphicon-comment"><?= $post->total_comments ?></span>
                </div>
                <div class="col-xs-6">                                      
                    <?=(Yii::$app->user->id != $post->user_id ) ?
                            Html::tag('span', Html::encode($post->total_likes), ['id' => 'like-' . $post->id, 'class' => 'glyphicon glyphicon-heart text-danger', 'style' => "cursor: pointer", 'onclick' => 'addLike(' . $post->id . ')']) :
                            Html::tag('span', Html::encode($post->total_likes), ['id' => 'like-' . $post->id, 'class' => 'glyphicon glyphicon-heart text-danger'])
                    ?>
                </div>                        
            </div>
            <h8>Posted at <?= $post->created_at ?></h8>
            </br>
            <h8>Author: <?= $post->user->name ?></h8>  
        </div>
    </div>
</div>

<?php 
Modal::begin(['header'=> '<h4>Add Comment User : '.(Yii::$app->user->isGuest ? 'Anonymous' : Blogger::findOne(['id'=>$currentUser])->name ).'</h4>',
    'id'=> 'modal-'.$post->id,
    'size'=>'modal-md']);
echo "<div id=modalContent-".$post->id."></div>";
Modal::end();
?>

<?php
$this->registerJsFile('@web/js/j.js');  
$this->registerJs('window.onload = editPreviewText()');      

                
   
   