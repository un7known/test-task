<?php

namespace app\controllers\api;

use Yii;
use app\models\User;
use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class UserController extends Controller
{
    public function actionLogin()
    {
        $post = Yii::$app->getRequest()->post();

        if (isset($post['login']) && isset($post['password'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user) {
                if (Yii::$app->getSecurity()->validatePassword($post['password'], $user->password)) {
                    return [
                        'status' => 1,
                        'data' => [
                            ['token' => $user->token],
                        ],
                    ];
                } else {
                    $errors[] = 'Ошибка авторизации';
                }
            }
        } else {
            $errors[] = 'Пользователь не найден';
        }
        return ['status' => -1, 'message' => $errors];
    }
    public function actionPasswordRecovery()
    {
        $post = Yii::$app->getRequest()->post();
        if (count($post) === 1 && isset($post['login'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user) {
                $user->code = User::generateCode();
                if ($user->save()) {
                    return [
                        'status' => 1,
                        'data' => [
                            ['code' => $user->code],
                        ],
                    ];
                }
                $errors = $user->getErrors();
            } else {
                $errors[] = 'Пользователь не найден';
            }
        } elseif (isset($post['login']) && isset($post['code']) && isset($post['password'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user) {
                if ($user->code === $post['code']) {
                    $user->password = Yii::$app->getSecurity()->generatePasswordHash($post['password']);
                    $user->token = Yii::$app->getSecurity()->generateRandomString();
                    $user->code = User::generateCode();
                    if ($user->save()) {
                        return [
                            'status' => 1,
                            'data' => [
                                ['token' => $user->token],
                            ],
                        ];
                    }
                } else {
                    $errors[] = 'Неверный код';
                }
            } else {
                $errors[] = 'Пользователь не найден';
            }
        } else {
            $errors[] = 'Неправильный запрос';
        }
        return ['status' => -1, 'message' => $errors];
    }
    public function actionRegister()
    {
        $post = Yii::$app->getRequest()->post();
        if (count($post) === 1 && isset($post['login'])) {
            $user = new User();
            $user->login = $post['login'];
            $user->code = User::generateCode();
            if ($user->save()) {
                return [
                    'status' => 1,
                    'data' => [
                        ['code' => $user->code],
                    ],
                ];
            } else {
                $errors = $user->getErrors();
            }
        } elseif (isset($post['login']) && isset($post['code']) && isset($post['password'])) {
            $user = User::findOne(['login' => $post['login']]);
            if ($user) {
                if ($user->code === $post['code']) {
                    $user->password = Yii::$app->getSecurity()->generatePasswordHash($post['password']);
                    $user->token = Yii::$app->getSecurity()->generateRandomString();
                    $user->code = User::generateCode();
                    if ($user->save()) {
                        return [
                            'status' => 1,
                            'data' => [
                                ['token' => $user->token],
                            ],
                        ];
                    }
                } else {
                    $errors[] = 'Неверный код';
                }
            } else {
                $errors[] = 'Пользователь не найден';
            }
        } else {
            $errors[] = 'Неправильный запрос';
        }
        return ['status' => -1, 'message' => $errors];
    }
}
