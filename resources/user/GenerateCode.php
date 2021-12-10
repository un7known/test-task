<?php

namespace app\resources\user;

use app\resources\User;
use app\resources\ValidationModel;

/**
 * GenerateCode.
 */
class GenerateCode extends ValidationModel
{
    /**
     * Сценарий регистрации.
     *
     * @var string
     */
    public const SCENARIO_REGISTER = 'register';
    /**
     * Сценарий вотсстановление пароля.
     *
     * @var string
     */
    public const SCENARIO_RECOVERY = 'recovery';
    /**
     * login.
     *
     * @var string
     */
    public $login;
    /**
     * _user.
     *
     * @var User
     */
    protected $_user;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['login'];
        $scenarios[self::SCENARIO_RECOVERY] = ['login'];

        return $scenarios;
    }

    public function rules()
    {
        return [
            ['login', 'trim'],
            [['login'], 'string', 'length' => 10, 'on' => self::SCENARIO_REGISTER],
            [['login'], 'required'],
        ];
    }

    public function doAction()
    {
        if ($this->validate()) {
            if ($this->_user = $this->scenario === 'register') {
                $this->_user = new User();
                $this->_user->login = $this->login;
            } else {
                $this->_user = User::findUser($this->login);
            }
            $this->_user->generateCode();
            if ($this->_user->save()) {
                return ['code' => $this->_user->code];
            } else {
                $this->addErrors($this->_user->errors);
            }
        }

        return false;
    }
}
