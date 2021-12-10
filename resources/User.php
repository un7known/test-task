<?php

namespace app\resources;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "users".
 *
 * @property int         $id
 * @property string      $login
 * @property string      $hash
 * @property null|string $token
 * @property null|string $code
 */
class User extends \yii\db\ActiveRecord
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
            [['login', 'token'], 'unique'],
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
            'token' => 'Token',
            'code' => 'Code',
        ];
    }

    /**
     * Проверка пароля.
     *
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->hash);
    }

    public function validateCode($code)
    {
        return $this->code === $code;
    }

    /**
     *  Генерация кода для авторизации.
     *
     * @param int $length
     */
    public function generateCode($length = 6)
    {
        $result = '';
        for ($i = 1; $i <= $length; ++$i) {
            $result .= random_int(0, 9);
        }
        $this->code = $result;
    }

    /**
     * Генерация токена.
     */
    public function generateToken()
    {
        $this->token = Yii::$app->getSecurity()->generateRandomString();
        while (!$this->validate('token')) {
            $this->token = Yii::$app->getSecurity()->generateRandomString();
        }
    }

    /**
     * Генерируем пароль.
     *
     * @param string $password
     */
    public function generatePassword($password)
    {
        $this->hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Поиск пользователя.
     *
     * @param string $login
     *
     * @throws NotFoundHttpException
     *
     * @return app\resources\User
     */
    public static function findUser($login)
    {
        if (($user = User::findOne(['login' => $login])) !== null) {
            return $user;
        }

        throw new NotFoundHttpException('Пользователь не найден');
    }
}
