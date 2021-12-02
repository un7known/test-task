<?php

namespace app\models;

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
class User extends \app\resources\User implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */


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

}
