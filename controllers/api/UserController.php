<?php

namespace app\controllers\api;

use app\resources\user\GenerateCode;
use app\resources\user\LoginUser;
use app\resources\user\VerifyCode;
use Yii;

/**
 * Контроллер для регистрации, восстановления пароля и получения токена.
 */
class UserController extends ApiController
{
    public function actions()
    {
        return [];
    }

    /**
     * Получение токена по логину/пароля.
     *
     * @return string
     */
    public function actionLogin()
    {
        return $this->doActionByEntity(new LoginUser());
    }

    /**
     * Восстановление пароля.
     *
     * @return string
     */
    public function actionPasswordRecovery()
    {
        return $this->registerOrRecovery(false);
    }

    /**
     * Регистрация.
     *
     * @return string
     */
    public function actionRegister()
    {
        return $this->registerOrRecovery();
    }

    /**
     * Функция для регистрации и проверки пароля.
     *
     * @param bool $register Для регистрациия $register = true. Для восстановления паролья $register = false
     *
     * @return array
     */
    protected function registerOrRecovery($register = true)
    {
        if (count(Yii::$app->getRequest()->post()) === 1) {
            if ($register) {
                return $this->doActionByEntity(new GenerateCode(['scenario' => GenerateCode::SCENARIO_REGISTER]));
            }

            return $this->doActionByEntity(new GenerateCode(['scenario' => GenerateCode::SCENARIO_RECOVERY]));
        }else {
            return $this->doActionByEntity(new VerifyCode());

        }
    }
}
