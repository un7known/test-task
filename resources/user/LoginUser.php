<?php

namespace app\resources\user;

use app\resources\User;
use app\resources\ValidationModel;

/**
 * LoginUser.
 */
class LoginUser extends ValidationModel
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
     * _user.
     *
     * @var User
     */
    protected $_user;

    public function rules()
    {
        return [
            ['login', 'trim'],
            [['login', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * validatePassword.
     *
     * @param mixed $attribute
     * @param mixed $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = User::findUser($this->login);
            if (!$this->_user || !$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, 'Некорректный логин или пароль.');
            }
        }
    }

    public function doAction()
    {
        if ($this->validate()) {
            return ['token' => $this->_user->token];
        }

        return false;
    }
}
