<?php

namespace common\components\tools\sendcloud;

use common\components\tools\sendcloud\lib\SendCloud;
use common\components\tools\sendcloud\lib\util\Mail;
use common\components\tools\sendcloud\lib\util\TemplateContent;
use common\components\tools\sendcloud\lib\util\Mimetypes;
use common\components\tools\sendcloud\lib\util\Attachment;

class MailProxy {

    /*
     * 用于生成发送邮件的变量
     */
    private static function getVars($var,$count)
    {
        $data = [];
        foreach($var as $k=>$v){
            for($i=0;$i<$count;$i++){
                $data[$k][]=$v;
            }
        }
        return $data;
    }
    /**
     * sendCloud Eamil
     * 邮件发送接口
     * 通过邮件模板发送邮件
     * @param
     */
    public static function sendEmail($subData)
    {
        $sendcloud=new SendCloud(\Yii::$app->params['sendCloudApiUser'], \Yii::$app->params['sendCloudApiKey'],'v2');
        $mail=new Mail();
        $mail->setFrom($subData['info']['from']);
        $mail->setXsmtpApi(json_encode(array(
            'to'=>$subData['info']['to'],
            'sub'=>self::getVars($subData['vars'], count($subData['info']['to'])),
        )));
        $mail->setSubject($subData['info']['subject']);
        $mail->setRespEmailId(true);
        $templateContent=new TemplateContent();
        $templateContent->setTemplateInvokeName($subData['info']['template']);
        $mail->setTemplateContent($templateContent);
        return $sendcloud->sendTemplate($mail);
    }


    /**
     * 带附件邮件发送接口
     * @param
     */
    public static function sendMailWithAttach($subData){
        $sendcloud=new SendCloud(\Yii::$app->params['sendCloudApiUser'], \Yii::$app->params['sendCloudApiKey'],'v2');
        $mail=new Mail();
        $mail->setFrom($subData['info']['from']);
        $mail->setFromName($subData['info']['from_name']);
        $mail->setXsmtpApi(json_encode(array(
            'to'=>$subData['info']['to'],
            'sub'=>self::getVars($subData['vars'], count($subData['info']['to'])),
        )));
        $mail->setSubject($subData['info']['subject']);
        $mail->setRespEmailId(true);
        $templateContent=new TemplateContent();
        $templateContent->setTemplateInvokeName($subData['info']['template']);
        $mail->setTemplateContent($templateContent);

        foreach($subData['info']['attach_path'] as $v){
            $file = $v;
            $handle = fopen($file,'rb');
            $content = fread($handle,filesize($file));
            $filetype= Mimetypes::getInstance()->fromFilename($file);
            $attachment=new Attachment();
            $attachment->setType($filetype);
            $attachment->setContent($content);
            $attachment->setFilename(\common\components\tools\LanHelper::get_basename($file));
            $mail->addAttachment($attachment);
            fclose($handle);
        }

        return $sendcloud->sendTemplate($mail);
    }


    /**
     * 发送普通邮件
     */
    public static function sendCommonMail($subData)
    {
        $sendcloud=new SendCloud(\Yii::$app->params['sendCloudApiUser'], \Yii::$app->params['sendCloudApiKey'],'v2');
        $mail=new Mail();
        $mail->setFrom($subData['from']);
        $mail->addTo($subData['to']);
        $mail->setFromName($subData['from_name']);
        $mail->setSubject($subData['subject']);
        $mail->setContent($subData['content']);
        $mail->setRespEmailId(true);
        return $sendcloud->sendCommon($mail);
    }
}
