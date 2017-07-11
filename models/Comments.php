<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use app\models\Blogger;

use Yii;

/**
 * This is the model class for table "Comments".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $text
 * @property integer $user
 * @property integer $created_at
 * @property integer $update_at
 *
 * @property Post $post
 */
class Comments extends \yii\db\ActiveRecord
{
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
    public static function tableName()
    {
        return 'Comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'text'], 'required'],
            [['post_id', 'user'], 'integer'],
            [['text'], 'string'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**Blogger::findOne(['id' => $comment->user])->name
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'text' => 'Text',
            'user' => 'User', 
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
    
    public function getCommentCreator() {
        $user = $this->user;
        return $user != '' ? Blogger::findOne(['id' => $user])->name : 'Anonymous';
    }

}
