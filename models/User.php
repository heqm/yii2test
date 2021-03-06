<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $login
 * @property string $passwordHash
 * @property string $email
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['login', 'email', 'passwordHash'], 'required'],
            [['login', 'email', 'passwordHash'], 'string', 'max' => 255, 'min' => 3],
            ['login', 'unique'],
            ['email','email'],
            // passwordHash is validated by validatePassword()
            ['passwordHash', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * Finds user by login
     *
     * @param  string      $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        if ($user = User::find()->where(['login' => $login])->one()) {
            return new static($user);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
    }

    /**
     * Validates passwordHash
     *
     * @param  string  $passwordHash passwordHash to validate
     * @return boolean if passwordHash provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->passwordHash);
    }
}
