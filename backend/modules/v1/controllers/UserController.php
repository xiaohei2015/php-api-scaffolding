<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use backend\models\LoginForm;

/**
 * Default controller for the `v1` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\modelsBiz\UserBiz';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];

    /**
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');
        $model->rememberMe = Yii::$app->request->post('remember_me');
        if ($model->login()) {
            return \backend\models\AdminUser::getUserInfo();
        } else {
            return $model;
        }
    }

    public function actionIsLogin()
    {
        return \backend\models\AdminUser::getUserInfo();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        var_dump(456);
        exit;
    }
}
