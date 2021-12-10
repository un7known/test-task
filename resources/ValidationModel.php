<?php
namespace app\resources;

use yii\base\Model;

/**
 * класс для валидаторов
 */
abstract class ValidationModel extends Model
{
    /**
     * Действие валидации
     *
     * @return bool|array
     */
    abstract public function doAction();
    /**
     * Форматирование ошибок в массив
     *
     * @return array|bool
     */
    public function formatErrors()
    {
        if (isset($this->errors)) {
            $result = [];
            foreach ($this->errors as $attribute => $error) {
                $result[] = implode(' ', $error);
            }

            return $result;
        }

        return false;
    }
}
