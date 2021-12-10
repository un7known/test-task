<?php

namespace app\resources\user;

use app\resources\User;
use app\resources\ValidationModel;

/**
 * LoginUser.
 */
class VerifyCode extends ValidationModel
{
    /**
     * login.
     *
     * @var string
     */
    public $login;
    /**
     * password.
     *
     * @var string
     */
    public $password;
    /**
     * code.
     *
     * @var string
     */
    public $code;

    /**
     * _user.
     *
     * @var User
     */
    protected $_user;

    public function rules()
    {
        return [
            ['login', 'trim'],
            [['login', 'password', 'code'], 'required'],
            ['code', 'validateCode'],
        ];
    }

    /**
     * validateCode.
     *
     * @param mixed $attribute
     * @param mixed $params
     */
    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = User::findUser($this->login);
            if (!$this->_user || !$this->_user->validateCode($this->code)) {
                $this->addError($attribute, 'Неверный код');
            }
        }
    }

    public function doAction()
    {
        if ($this->validate()) {
            $this->_user->generateToken();
            $this->_user->generatePassword($this->password);
            $this->_user->code = null;
            if ($this->_user->save()) {
                return ['token' => $this->_user->token];
            }
        }

        return false;
    }
}
