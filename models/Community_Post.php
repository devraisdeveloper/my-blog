<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "Community_Post".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $community_id
 * @property string $created_at
 * @property string $update_at
 *
 * @property Community $community
 * @property Post $post
 */
class Community_Post extends \yii\db\ActiveRecord {

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
        return 'Community_Post';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['post_id', 'community_id'], 'required'],
            [['post_id', 'community_id'], 'integer'],
      
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Community::className(), 'targetAttribute' => ['community_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'community_id' => 'Community ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity() {
        return $this->hasOne(Community::className(), ['id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost() {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

}
