<?php

namespace common\components\tools;

use Yii;
use yii\base\Component;
use common\modelsBiz\SysMsgAuthBiz;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class MsgAuthManager extends Component
{
    /**
     * 用户的ip
     * @return string
     */
    public static function userIp()
    {
        $IPaddress = '127.0.0.1';
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $IPaddress = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $IPaddress = getenv("HTTP_CLIENT_IP");
            } else {
                $IPaddress = getenv("REMOTE_ADDR");
            }
        }

        if (strstr($IPaddress, ','))
        {
            $ips = explode(',', $IPaddress);
            $IPaddress = $ips[0];
        }

        return $IPaddress;
    }

    /**
     * 执行发送任务
     * @param $phone
     * @param $params
     * @param $sign
     * @param $template_id
     * @return false|object
     */
    public static function executeSendSMS($phone, $params, $sign, $template_id)
    {
        $sms_config = Yii::$app->params['sms.config'];
        $config = [
            'app_key'    => $sms_config['app_key'],
            'app_secret' => $sms_config['app_secret'],
        ];

        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum($phone)
            ->setSmsParam($params)
            ->setSmsFreeSignName($sign)
            ->setSmsTemplateCode($template_id);

        return $resp = $client->execute($req);
    }

    /**
     * 发送消息
     */
    public static function sendMsg($phone, $product='xxx产品', $sign='身份验证', $template_id=null)
    {
        //权限验证
        $msgLast = \common\modelsBiz\SysMsgAuthBiz::find()
            ->andFilterWhere(['and', ['=', 'phone', $phone], ['>=', 'send_time', (time()-1*60)]])
            ->count();
        if($msgLast>=1){
            return '您提交的太快了，请稍后再试!';
        }

        $msgLast = \common\modelsBiz\SysMsgAuthBiz::find()
            ->andFilterWhere(['and', ['=', 'phone', $phone], ['>=', 'send_time', (time()-1*60*60)]])
            ->count();
        if($msgLast>=5){
            return '您提交的太快了，请稍后再试!';
        }

        $msgLast = \common\modelsBiz\SysMsgAuthBiz::find()
            ->andFilterWhere(['and', ['=', 'phone', $phone], ['>=', 'send_time', (time()-1*24*60*60)]])
            ->count();
        if($msgLast>=10){
            return '您提交的太快了，请稍后再试!';
        }

        $code = strval(rand(100000,999999));
        //发送短信
        $template_id = $template_id?:'SMS_12312312';
        $content = json_encode(array('code'=>$code,'product'=>$product));

        $result = self::executeSendSMS($phone,$content,$sign,$template_id);
        if(isset($result->result->err_code) && $result->result->err_code == 0){
            SysMsgAuthBiz::updateAll(array('status'=>1),'phone=:phone and  status=0',array(':phone'=>$phone));
            //记录验证码至数据库
            $model = new SysMsgAuthBiz();
            $model->phone = $phone;
            $model->send_time = time();
            $model->code = $code;
            $model->ip = self::userIp();
            $model->status = 0;
            @$model->save();
            Yii::warning("MsgAuthManager->sendMsg success phone:".$phone, "sendmsg");
            return true;
        }else{
            if(isset($result->sub_msg)){
                $error = $result->sub_msg;
            }elseif(isset($result->msg)){
                $error = $result->msg;
            }elseif(is_string($result)){
                $error = $result;
            }else{
                $error = serialize($result);
            }
            Yii::warning("MsgAuthManager->sendMsg error:".$error, "sendmsg");
            return $error;
        }
    }

    /**
     * 验证码验证
     */
    public static function verifyCode($phone, $code, $is_destroy=false)
    {
        $model_msg = \common\modelsBiz\SysMsgAuthBiz::find()
            ->andFilterWhere(['and', ['=', 'phone', $phone], ['=', 'status', 0], ['>=', 'send_time', (time()-Yii::$app->params["MsgTimeOut"])]])
            ->one();
        if(!isset($model_msg)){
            return '校验码已经失效, 请重新获取校验码';
        }
        if($model_msg->failed_count >= 3){
            return '输入错误的次数超过3次，请重新获取短信';
        }
        if($model_msg->code!=$code){
            \common\modelsBiz\SysMsgAuthBiz::updateAll(array('failed_count'=>$model_msg->failed_count+1),['id' => $model_msg->id]);
            if($model_msg->failed_count >= 2){
                return '输入错误的次数超过3次，请重新获取短信';
            }else{
                return '短信验证码有误';
            }
        }else{
            if($is_destroy){
                \common\modelsBiz\SysMsgAuthBiz::updateAll(['status' => 1],['phone' => $phone, 'status' => 0]);
            }
            return true;
        }
    }
}