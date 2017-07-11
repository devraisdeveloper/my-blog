<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "Community".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CommunityPost[] $communityPosts
 * @property Members[] $members
 */
class Community extends \yii\db\ActiveRecord {

    public $images = [];
    
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
        return 'Community';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'total_posts' => 'Total Posts',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityPosts() {
        return $this->hasMany(Community_Post::className(), ['community_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers() {
        return $this->hasMany(Members::className(), ['community_id' => 'id']);
    }

    public function getPosts() {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
                        ->viaTable('Community_Post', ['community_id' => 'id']);
    }
    
    public function getCreator(){
        return $this->hasOne(Blogger::className(),['id'=>'created_by']);
    }
    
    public function getIsDefaultPicture($image){
        return ($image == 'images/thumbnails/default-posts.jpg') ? true : false;
    }
    
    public function afterDelete() {
        foreach($this->images as $image){
            if(!$this->getIsDefaultPicture($image)){
               unlink(Yii::$app->basePath . '/web/' . $image); 
            }
        }
        parent::afterDelete();
    }
    
    public function beforeDelete() {
        $posts = $this->posts;
        foreach($posts as $post){
            $this->images[] = $post->picture;
        }

        return parent::beforeDelete();
    }

}
