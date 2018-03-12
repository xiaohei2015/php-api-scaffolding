<?php
namespace api\modules\v1\controllers;

use common\components\rest\ActiveController;
use Yii;
use common\modelsBiz\LoginForm;
use common\components\response\ReturnMsg;
use common\components\tools\ParamValidator;

class FuncController extends ActiveController
{
    public $modelClass = 'common\modelsBiz\UserBiz';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];

    public function actionSendEmail()
    {
        $to = [
            '306539332@qq.com' => '小黑'
        ];
        $subject = '关于股份分配的相关说明';
        $content = '<center>这是一封邮件<strong>正文</strong></center>';
        \common\components\tools\EmailManager::executeSendEmail($to, $subject, $content);
    }
}