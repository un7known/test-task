<?php

namespace app\controllers\api;

use Yii;
use yii\base\Exception;
use yii\rest\ActiveController;
use app\resources\ValidationModel;

/**
 * Контролер от которого наследуем други контролеры в API
 */
class ApiController extends ActiveController
{
    /**
     * Значение при успешном ответе.
     *
     * @var int
     */
    public const STATUS_SUCCESS = 1;
    /**
     * Значение при ошибке.
     *
     * @var int
     */
    public const STATUS_ERROR = -1;
    public $modelClass = 'app\resources\User';

    /**
     * Форматирование успешного ответа.
     *
     * @param mixed $data
     *
     * @return array
     */
    public function success($data)
    {
        return
            [
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ];
    }

    /**
     * Форматирование ответа с ошибкой.
     *
     * @param array $message
     *
     * @return array
     */
    public function error($message)
    {
        return
            [
                'status' => self::STATUS_ERROR,
                'message' => $message,
            ];
    }
    /**
     * Выполнение действия.
     *
     * @param  ValidationModel $model
     * @return array
     */
    public function doActionByEntity($model)
    {
        try {
            $model->load(Yii::$app->getRequest()->post(), '');
            if (($data = $model->doAction()) !== false) {
                return $this->success($data);
            }

            return $this->error($model->formatErrors());
        }
        catch (Exception $e) {
            return $this->error([$e->getMessage()]);
        }

    }
}
