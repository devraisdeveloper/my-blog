<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "Bloger".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Post[] $posts
 */
class Blogger extends \yii\db\ActiveRecord implements IdentityInterface {

    public $hashPassword = false;
    public $managementPosition;

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
        return 'Blogger';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email', 'status', 'username', 'password'], 'required'],
            [['status'], 'string'],
            [['name'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'string', 'min' => 6],
            [['name'], 'string', 'max' => 75],
            [['email'], 'string', 'max' => 150],
            [['email'], 'unique'],
            [['managementPosition'],'in','range'=>['admin','moderator']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts() {
        return $this->hasMany(Post::className(), ['user_id' => 'id']);
    }
    
    public function getBloggerName(){
        return $this->name;
    }
    
      public function getMembers() {
        return $this->hasMany(Members::className(), ['user_id' => 'id']);
    }

    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password) {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->password = \Yii::$app->security->generatePasswordHash($this->password, 10);
            return true;
        } else {
            return false;
        }
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return $this->authKey;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public function signup() {
        $this->name = \yii\helpers\HtmlPurifier::process($this->name);
        $this->username = \yii\helpers\HtmlPurifier::process($this->username);
        $this->email = $this->email;
        $this->password = $this->password;
        $this->status = "ENABLED";

        if (!$this->validate()) {
            return null;
        }

        return $this->save() ? $this : null;
    }

    public function assignRights($role) {
        try {
            $auth = Yii::$app->authManager;
            $bloggerRole = $auth->getRole($role);
            $auth->assign($bloggerRole, $this->getId());
        } catch (Exception $ex) {
            Yii::$app->getSession()->setFlash('error', 'Failed to assigne permissions ! Error code ' . $ex->getCode());
        }
    }

}
