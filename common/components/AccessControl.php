<?php

namespace common\components;

use Yii;
use common\components\exception\ExceptionHandler;

/**
 * Access Control Filter (ACF) Redefine.
 *
 * @author Johnny Hu
 */
class AccessControl extends \mdm\admin\components\AccessControl
{
    /**
     * overide
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            /*header('Content-type: application/json');
            header("Cache-Control: no-cache, must-revalidate");
            $json =  json_encode(['code'=>'5112','msg'=>'身份异常请重新登录']);
            echo $json;
            Yii::$app->end();*/
            ExceptionHandler::throwException('身份异常请重新登录');
        } else {
            header('Content-type: application/json');
            header("Cache-Control: no-cache, must-revalidate");
            $json =  json_encode(['code'=>'1','msg'=>'You are not allowed to perform this action']);
            echo $json;
            Yii::$app->end();
        }
    }
}