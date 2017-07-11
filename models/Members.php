<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "Members".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $community_id
 * @property string $created_at
 * @property string $update_at
 *
 * @property Blogger $user
 * @property Community $community
 */
class Members extends \yii\db\ActiveRecord {

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
        return 'Members';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'community_id'], 'required'],
            [['user_id', 'community_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Blogger::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Community::className(), 'targetAttribute' => ['community_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'community_id' => 'Community ID',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Blogger::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity() {
        return $this->hasOne(Community::className(), ['id' => 'community_id']);
    }
    
}
