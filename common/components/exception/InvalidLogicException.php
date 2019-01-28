<?php
/**
 * Johnny
 */

namespace common\components\exception;

use Yii;
use yii\base\UserException;
use yii\web\Response;
use yii\base\Model;

/**
 *
 */
class InvalidLogicException extends \yii\base\UserException
{
    public function __construct($data, $message = null, $code = 0, \Exception $previous = null)
    {
        if($data instanceof Model){
            $fmt_data = $data->getFirstErrors();
        }else{
            $fmt_data = $data;
        }
        Yii::$app->error->add($fmt_data, Yii::$app->params['response.code']['logic_invalid']['id']);
        parent::__construct($message, $code, $previous);
    }

    public function getName()
    {
        return 'InvalidLogicException';
    }
}
