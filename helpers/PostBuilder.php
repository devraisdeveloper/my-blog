<?php

namespace app\helpers;

use app\models\Post;
use app\helpers\PictureHelper;
use yii\web\UploadedFile;
use Yii;

class PostBuilder {
    public function createNewPost($data){
        $model = new Post();      
            $model->load($data);
            $model->user_id = Yii::$app->user->id;
            $model->total_likes = 0;
            $model->total_comments = 0;
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if (empty($model->imageFile)) {
                $model->picture = $model->DefaultPicture;
            } else {
                $helper = new PictureHelper();
                $imageName = strtolower($model->title);
                $model->picture = $helper->setPicture($imageName, $model);
            }
            if ($model->validate() && $model->save()) {
                return $model->id;
            } else {
                Yii::$app->getSession()->setFlash('error', 'Failed to create Post!!!');
                return false;
            }

    }

}
