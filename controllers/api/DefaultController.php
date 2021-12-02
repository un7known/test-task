<?php

namespace app\controllers\api;

use app\helpers\ApiController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Default controller for the `api` module.
 */
class DefaultController extends ApiController
{
    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * Renders the index view for the module.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->success(['login' => \Yii::$app->user->identity->login]);
    }
}
