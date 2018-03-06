<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\exception;

use Yii;
use yii\base\UserException;
use yii\web\Response;
use yii\base\Model;

/**
 *
 */
class ExceptionHandler extends \yii\base\UserException
{
    /**
     * Model Exception Handling
     */
    public static function throwNormalException($data)
    {
        throw new \Exception(json_encode($data));
    }

    /**
     * Model Exception Handling
     */
    public static function throwModelException($model)
    {
        Yii::$app->error->addMulti($model->getFirstErrors());
        throw new \Exception('Error Encountered');
    }

    public static function throwException($data)
    {
        if($data instanceof Model){
            self::throwModelException($data);
        }else{
            self::throwNormalException($data);
        }
    }
}
