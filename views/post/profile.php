<?php

use yii\bootstrap\Nav;
use app\models\Blogger;
?>

<h3><?= Blogger::findOne(['id'=> Yii::$app->user->id])->name.' Profile Page' ?></h3>

<?php echo Nav::widget([
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
            'label'=> 'View Groups',
            'url'=> ['community/list']
        ],
         [
            'label'=> 'Create Group',
             'url'=> ['community/create']
        ],
    ]
]);


if (!empty($posts)) {
    foreach ($posts as $post) {
        echo $this->render('post-template', ['post' => $post]);
    }
}else{
        echo $this->render('no-posts');
    }