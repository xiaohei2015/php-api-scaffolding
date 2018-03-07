<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * Menu controller for the `v1` module
 */
class CommonController extends ActiveController
{
    public $modelClass = '';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];

    /*public function actionCities()
    {
        return \common\modelsBiz\SysCityBiz::getAllCities();
    }*/

    public function actionProvinces()
    {
        return \common\modelsBiz\SysCityBiz::getProvinces();
    }

    public function actionCities()
    {
        $params = \Yii::$app->request->queryParams;
        return \common\modelsBiz\SysCityBiz::getCities($params['id']);
    }

    public function actionRegions()
    {
        $params = \Yii::$app->request->queryParams;
        return \common\modelsBiz\SysCityBiz::getRegions($params['id']);
    }

    public function actionAllProvinces()
    {
        return \common\modelsBiz\SysCityBiz::getAllProvinces();
    }

    public function actionAllCities()
    {
        $params = \Yii::$app->request->queryParams;
        return \common\modelsBiz\SysCityBiz::getAllCities($params['id']);
    }

    public function actionAllRegions()
    {
        $params = \Yii::$app->request->queryParams;
        return \common\modelsBiz\SysCityBiz::getAllRegions($params['id']);
    }

    public function actionUpload()
    {
        $model = new \common\modelsBiz\UploadImgForm();
        if (Yii::$app->request->isPost) {
            $model->attachment = \yii\web\UploadedFile::getInstanceByName('attachment');
            if($model->attachment){
                if ($model->validate()) {
                    if($result = $model->saveFile()){
                        return ['url'=>$result];
                    }
                }
            }else{
                $model->addError('attachment','请选择文件!');
            }
        }
        return $model;
    }
}
