<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use app\helpers\PictureHelper;
use app\models\Comments;
use app\models\Likes;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "Post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $picture
 * @property string $text
 * @property integer $total_likes
 * @property integer $total_comments
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Comments[] $comments
 * @property Likes[] $likes
 * @property Bloger $user
 */
class Post extends \yii\db\ActiveRecord {

    public $imageFile;
    public $oldValues = [];

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Post';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'picture', 'text', 'total_likes', 'total_comments', 'title'], 'required'],
            [['user_id', 'total_likes', 'total_comments'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['picture'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Blogger::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'picture' => 'Picture',
            'text' => 'Text',
            'total_likes' => 'Total Likes',
            'total_comments' => 'Total Comments',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments() {
         return $this->hasMany(Comments::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes() {
        return $this->hasMany(Likes::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Blogger::className(), ['id' => 'user_id']);
    }

    public function getDefaultPicture() {
        return 'images/thumbnails/default-posts.jpg';
    }
    
    public function getIsDefaultPicture(){
     return ($this->picture == 'images/thumbnails/default-posts.jpg') ? true : false;
    }

    public function savePictureFile() {
        $this->imageFile->saveAs($this->picture);
    }
    
    public function getCommunityPosts() {
        return $this->hasMany(Community_Post::className(), ['post_id' => 'id']);
    }
    
    public function LinkedPosts() {
        $values = [];
        $linkedPosts = $this->hasMany(Community_Post::className(), ['post_id' => 'id']);
        if (!empty($linkedPosts)) {
            foreach ($linkedPosts as $post) {
                $values[] = $post->id;
            }
            return $values;
        }
        return $values;
    }
    
    public function getIsGroupPost(){
        return (!empty($this->communityPosts) ? true : false);
    }

    public function getPictureInfo() {
        $path = Url::to('@webroot/images/thumbnails/');
        $url = Url::to('@web/images/thumbnails/');
        $ext = pathinfo($this->picture, PATHINFO_EXTENSION);
        $file = pathinfo($this->picture, PATHINFO_FILENAME);
        $filename = $file . '.' . $ext;
        $alt = $this->title . "'s Profile Picture";
        $imageInfo = ['alt' => $alt];
        if (file_exists($path . $filename)) {
            $imageInfo['url'] = $url . $filename;
        } else {
            $imageInfo['url'] = $url . 'default-post.jpg';
        }
        return $imageInfo;
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            if ($this->picture != $this->DefaultPicture) {
                $this->savePictureFile();
            }
            parent::afterSave($insert, $changedAttributes);
        } else {
            if ($this->oldValues['picture'] != $this->picture) {
                $this->savePictureFile();
                if($this->isDefaultPicture){
                unlink(Yii::$app->basePath . '/web/' . $this->oldValues['picture']);
                }
            }
        }
    }

    public function beforeSave($insert) {
       if(!$this->isNewRecord){
           $this->oldValues = $this->oldAttributes;         
       }
       
       return parent::beforeSave($insert);
    }
    
    public function afterDelete() {
        if (!$this->isDefaultPicture) {
            unlink(Yii::$app->basePath . '/web/' . $this->attributes['picture']);
        }
        parent::afterDelete();
    }
}
    