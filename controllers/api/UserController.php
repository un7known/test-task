<?php

namespace app\controllers\api;

use Yii;
use app\resources\User;
use yii\base\ErrorException;
use app\helpers\ApiController;
use yii\web\NotFoundHttpException;

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
        $post = Yii::$app->getRequest()->post();

        try {
            $user = $this->findUser($post['login']);
            if ($user->load($post, '') && $user->validate()) {
                return $this->success(['token' => $user->token]);
            }
            $errors = $user->formatErrors();

            return $this->error($errors);
        } catch (ErrorException $e) {
            return $this->error([$e->getMessage()]);
        }
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
        $post = Yii::$app->getRequest()->post();

        try {
            if (count($post) === 1) {
                $user = $register ? new User() : $user = $this->findUser($post['login']);
                $user->scenario = User::SCENARIO_GENERATE_CODE;
                if ($user->load($post, '') && $user->validate() && $user->generateCode()) {
                    return $this->success(['code' => $user->code]);
                }
                $errors = $user->formatErrors();
            } else {
                $user = $this->findUser($post['login']);
                $user->scenario = User::SCENARIO_VERIFY_CODE;
                if ($user->load($post, '') && $user->validate()) {
                    $user->genPassAndToken();
                    $user->save(false);

                    return $this->success(['token' => $user->token]);
                }
                $errors = $user->formatErrors();
            }

            return $this->error($errors);
        } catch (ErrorException $e) {
            return $this->error([$e->getMessage()]);
        }
    }


    /**
     * Поиск пользователя
     *
     * @param  string $login
     * @return app\resources\User
     * @throws NotFoundHttpException
     */
    protected function findUser($login)
    {
        if (($user = User::findOne(['login' => $login])) !== null) {
            return $user;
        }

        throw new NotFoundHttpException('Пользователь не найден');
    }
}
