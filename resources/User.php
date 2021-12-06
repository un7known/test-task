<?php

namespace app\resources;

use Yii;

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
     * Сценарий при генерации кода.
     *
     * @var string
     */
    public const SCENARIO_GENERATE_CODE = 'generate_code';
    /**
     * Сценарий при проверке кода.
     *
     * @var string
     */
    public const SCENARIO_VERIFY_CODE = 'verify_code';

    /**
     * Пароль из запроса для проверки с hash.
     *
     * @var string
     */
    public $password = '';

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
            ['password', 'required', 'except' => self::SCENARIO_GENERATE_CODE],
            ['code', 'required', 'on' => self::SCENARIO_VERIFY_CODE],
            ['code', 'validateCode', 'on' => self::SCENARIO_VERIFY_CODE],
            [['login'], 'string', 'length' => 10],
            [['hash', 'password', 'token', 'code'], 'string', 'max' => 255],
            [['login', 'token'], 'unique'],
            ['password', 'validatePassword', 'except' => self::SCENARIO_VERIFY_CODE],
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

    /**
     * Проверка пароля.
     *
     * @param string $attribute
     * @param array  $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!\Yii::$app->getSecurity()->validatePassword($this->password, $this->hash)) {
            $this->addError($attribute, 'Неправильный пароль');
        }
    }


    /**
     * Форматирование ошибок в массив
     *
     * @return array|bool
     */
    public function formatErrors()
    {
        if (isset($this->errors)) {
            $result = [];
            foreach ($this->errors as $attribute => $error) {
                $result[] = implode(' ', $error);
            }

            return $result;
        }

        return false;
    }

    /**
     *  Генерация и сохранения кода для авторизации.
     *
     * @param mixed $length
     */
    public function generateCode($length = 6)
    {
        $result = '';
        for ($i = 1; $i <= $length; ++$i) {
            $result .= random_int(0, 9);
        }
        $this->code = $result;
        if ($this->save()) {
            return true;
        }

        return false;
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
     * Генерируем пароля, токен, и сбрасываем код.
     */
    public function genPassAndToken()
    {
        $this->hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $this->generateToken();
        $this->code = null;
    }

    /**
     * Проверка на совпадение пароля.
     *
     * @param string $attribute
     * @param array  $params
     */
    public function validateCode($attribute, $params)
    {
        $_attributes = $this->oldAttributes;
        $atributes = $this->attributes;
        if ($atributes['code'] !== $_attributes['code']) {
            $this->addError('code', 'Неправильный код');
        }
    }


}
