<?php
namespace api\modules\v1\controllers;

use common\components\rest\ActiveController;
use Yii;
use common\modelsBiz\LoginForm;
use common\components\response\ReturnMsg;
use common\components\tools\ParamValidator;

class UserController extends ActiveController
{
    public $modelClass = 'common\modelsBiz\UserBiz';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];

    /**
     * This method implemented to demonstrate the receipt of the token.
     * Do not use it on production systems.
     * @return string AuthKey or model with errors
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        Yii::warning(json_encode(\Yii::$app->getRequest()->getBodyParams()));

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return \common\modelsBiz\UserBiz::getUserInfo();
        } else {
            return $model;
        }
    }

    public function actionIsLogin()
    {
        return \common\modelsBiz\UserBiz::getUserInfo();
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if($user = \common\modelsBiz\UserBiz::findIdentityByAccessToken(Yii::$app->request->post('access-token'))){
            $user->removeAccessToken();
            $user->save();
        }
        Yii::$app->user->logout();
    }

    public function actionSendMsg()
    {
        //param validator
        $validator = new ParamValidator();
        $paramConfig = [];
        $paramConfig[] = $validator::numberParam('phone',array('title'=>'手机号码'));
        if(!$params = $validator->validateParams($paramConfig)){
            return $validator->getError();
        }
        $result = \common\components\tools\SmsManager::send($params['phone']);
        if($result === true){
            return ReturnMsg::success('发送成功！');
        }else{
            return ReturnMsg::fail($result);
        }
    }

    public function actionVerifyMsg()
    {
        //param validator
        $validator = new ParamValidator();
        $paramConfig = [];
        $paramConfig[] = $validator::numberParam('phone',array('title'=>'手机号码'));
        $paramConfig[] = $validator::numberParam('code',array('title'=>'验证码'));
        if(!$params = $validator->validateParams($paramConfig)){
            return $validator->getError();
        }
        $result = \common\components\tools\SmsManager::verifyCode($params['phone'],$params['code']);
        if($result === true){
            return ReturnMsg::success('验证成功！');
        }else{
            return ReturnMsg::fail($result);
        }
    }
}