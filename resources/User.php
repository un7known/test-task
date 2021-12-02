<?php

namespace app\resources;

use Yii;


/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $hash
 * @property string|null $token
 * @property string|null $code
 */
class User extends \yii\db\ActiveRecord
{
    public string $password = '';
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
            [['hash', 'password', 'token', 'code'], 'string', 'max' => 255],
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
            'password' => 'Password',
            'token' => 'Token',
            'code' => 'Code',
        ];
    }



    public function login()
    {
        if (\Yii::$app->getSecurity()->validatePassword($this->password, $this->hash)) {
            return true;
        }
        return false;
    }

    public function generateCode($length = 6)
    {
        $result = '';
        for ($i = 1; $i <= $length; $i++) {
            $result .= random_int(0, 9);
        }
        $this->code = $result;
        if ($this->save()) {
            return true;
        }
        return false;
    }
    public function verifyCode()
    {
        $_attributes = $this->oldAttributes;
        $atributes = $this->attributes;
        if ($atributes['code'] === $_attributes['code']){
            $this->hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $this->token = Yii::$app->getSecurity()->generateRandomString();
            while (!$this->validate('token')) {
                $this->token = Yii::$app->getSecurity()->generateRandomString();
            }
            $this->code = null;
            if ($this->save()) {
                return true;
            }
        }
        return false;

    }
}
