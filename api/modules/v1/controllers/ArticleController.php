<?php
namespace api\modules\v1\controllers;

use Yii;
use common\components\rest\ActiveController;

class ArticleController extends ActiveController
{
    public $modelClass = 'common\modelsBiz\ArticleBiz';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];

    public function actions()
    {
        return array_merge(
            parent::actions(),
            [
                'list' => [
                    'class' => 'yii\rest\IndexAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'prepareDataProvider' => function ($action) {
                        $model = new $this->modelClass;
                        return $model->search(Yii::$app->request->getBodyParams());
                    }
                ],
                'view' => [
                    'class' => 'common\components\rest\ViewAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'execute' => function ($action, $model) {
                        $model->save();
                        return $model;
                    }
                ],
            ]
        );
    }

    public function actionApply()
    {
        $params = Yii::$app->request->getBodyParams();
        $params['is_recommend'] = @$params['is_recommend']==1?1:0;
        //validation
        if(!isset($params['jd_ids']) || !is_array($params['jd_ids'])){
            GbException::throwException('参数jd_ids有误');
        }
        if(!isset($params['profile_id']) || !$params['profile_id']){
            GbException::throwException('参数profile_id有误');
        }
        if(!\xcx\modelsBiz\GbJdProfileBiz::isMyProfile($params['profile_id'])){
            GbException::throwException('参数profile_id有误');
        }

        //transaction
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $model = new \xcx\modelsBiz\GbJdProfileBiz();
            if(!$model->batchApplyJds($params)){
                throw new \Exception('保存失败');
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            if($model->hasErrors()){
                return $model;
            }else
                \common\components\exception\GbException::throwException($e->getMessage());
        }

        return;
    }
}