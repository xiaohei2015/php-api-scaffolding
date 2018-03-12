<?php

namespace common\components\tools;

use Yii;
use yii\base\Component;

class EmailManager extends Component
{
    /**
     * execute the send mail action
     * @param $to, e.g. '306539332@qq.com', or ['306539332@qq.com'=>'小黑']
     * @param $subject, e.g. '邮件主题'
     * @param $content, e.g. '邮件内容'
     * @return bool
     */
    public static function executeSendEmail($to,$subject,$content)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($to); //要发送给那个人的邮箱
        $mail->setSubject($subject); //邮件主题
        $mail->setHtmlBody($content); //发送的消息内容
        return $mail->send();
    }
}