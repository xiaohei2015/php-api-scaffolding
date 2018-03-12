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
        $mail= Yii::$app->mailer->compose();
        $mail->setTo('306539332@qq.com'); //要发送给那个人的邮箱
        $mail->setSubject("邮件主题"); //邮件主题
        $mail->setTextBody('测试text'); //发布纯文字文本
        $mail->setHtmlBody("测试html text"); //发送的消息内容
        var_dump($mail->send());
    }
}