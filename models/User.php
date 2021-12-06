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

    /**
     * Поиск по токену
     *
     * @param  string $token
     * @param  mixed $type
     * @return static|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return self::findOne(['token' => $token]);
    }

    /**
     * Поиск по username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['login' => $username]);
    }

    /**
     * Возравщает ID
     *
     * @return int|string
     */
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
