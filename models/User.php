<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string|null $token
 * @property string|null $code
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['login'], 'string', 'length' => 10],
            [['password', 'token', 'code'], 'string', 'max' => 255],
            [['login'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'token' => 'Token',
            'code' => 'Code',
        ];
    }

    public static function findIdentity($id)
    {
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

        return self::findOne(['token' => $token]);
    }

    public static function findByUsername($username)
    {
        return self::findOne(['login' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
    }
    public function getAuthKey()
    {
    }
    public static function generateCode($length = 6)
    {
        $result = '';
        for ($i = 1; $i <= $length; $i++) {
            $result .= random_int(0, 9);
        }
        return $result;
    }
}
