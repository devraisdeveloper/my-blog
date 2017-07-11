<?php

namespace app\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\base\Exception;

class PictureHelper{
    
  public function setPicture($imageName, $post) {
    /*  echo '<pre>';
      print_r($post);
      echo '</pre>';
      exit();*/
        $randNumber = mt_rand(10, 1000);
        $tempImageName = 'images/thumbnails/' . $imageName . $randNumber . '.' . $post->imageFile->extension;
        $files = FileHelper::findFiles('images/thumbnails/');
        while (true) {
            if (self::verifyPictureName($tempImageName, $files)) {
                return $tempImageName;
            } else {
                $randNumber = mt_rand(10, 1000);
                $previousName = explode('.', $tempImageName);
                $previousName[0] .= '/' . $randNumber . '.' . $post->imageFile->extension;
                $tempImageName = $previousName[0];
            }
        }
    }
    
    protected static function verifyPictureName($tempImageName, $files) {
        foreach ($files as $picture) {
            if ($picture == $tempImageName) {
                return false;
            }
        }
        return true;
    }
    
    public function setNewPicture($imageName, $post){
       $tempImageName = $this->setNewPicture($imageName, $post);
       
    }
    
}

