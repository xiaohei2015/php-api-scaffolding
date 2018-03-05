<?php

namespace common\components\response;

use Yii;
use yii\base\Model;

/**
 *
 */
class ReturnMsg extends Model
{
    public static function fail($msg, $field = '')
    {
        $model = new self();
        $model->addError($field, $msg);
        return $model;
    }

    public static function success($msg = '')
    {
        return $msg;
    }
}
