<?php

namespace app\controllers\api;

use Yii;
use app\resources\User;
use app\helpers\ApiController;

/**
 * Default controller for the `api` module
 */
class UserController extends ApiController
{
    public function actions()
    {
        return [];
    }

    public function actionLogin()
    {
        $post = Yii::$app->getRequest()->post();
        if (isset($post['login']) && isset($post['password'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user && $user->load($post, '') && $user->login()) {
                return $this->success(['token' => $user->token]);
            }
        }
        return $this->error(['Ошибка авторизации']);
    }
    public function actionPasswordRecovery()
    {
        return $this->resterOrRecovery(false);
    }
    public function actionRegister()
    {
        return $this->resterOrRecovery();
    }

    protected function resterOrRecovery($register = true){
        $post = Yii::$app->getRequest()->post();
        if (isset($post['login']) && count($post) === 1) {
            if ($register) {
                $user = new User();
            } else {
                $user = User::findOne(['login' => $post['login']]);
            }
            if ($user) {
                if ($user->load($post, '') && $user->generateCode()) {
                    return $this->success(['code' => $user->code]);
                }
                $errors = $user->getErrors();
            } else {
                $errors[] = 'Пользователь не найден';
            }
        } elseif (isset($post['login']) && isset($post['code']) && isset($post['password'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user) {
                if ($user->load($post, '') && $user->verifyCode()) {
                    return $this->success(['token' => $user->token]);
                } else {
                    $errors[] = 'Неверный код';
                }
            } else {
                $errors[] = 'Пользователь не найден';
            }
        } else {
            $errors[] = 'Неправильный запрос';
        }

        return $this->error($errors);
    }
}
