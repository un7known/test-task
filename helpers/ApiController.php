<?php

namespace app\helpers;

use yii\rest\ActiveController;

class ApiController extends ActiveController
{
    public $modelClass = 'app\resources\User';

    public const STATUS_SUCCESS = 1;
    public const STATUS_ERROR = -1;


    public function success($data){
        return
            [
                'status' => self::STATUS_SUCCESS,
                'data' => $data
            ];
    }
    public function error($message)
    {
        return
            [
                'status' => self::STATUS_ERROR,
                'message' => $message
            ];
    }
}
