<?php

namespace backend\modules\v1\controllers;

use common\modelsBiz\ArticleBiz;
use Yii;
use common\components\rest\ActiveController;
use yii\data\ActiveDataProvider;

/**
 * Menu controller for the `v1` module
 */
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
                'index' => [
                    'class' => 'yii\rest\IndexAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'prepareDataProvider' => function ($action) {
                        $model = new $this->modelClass;
                        return $model->search(\Yii::$app->request->queryParams);
                    }
                ],
                'create' => [
                    'class' => 'common\components\rest\CreateAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'execute' => function ($action) {
                        $model = new $this->modelClass();
                        $params = Yii::$app->getRequest()->getBodyParams();
                        $model->load($params, '');
                        $model->save();
                        return $model;
                    }
                ],
                'update' => [
                    'class' => 'common\components\rest\UpdateAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'execute' => function ($action, $model) {
                        $params = Yii::$app->getRequest()->getBodyParams();
                        $model->load($params, '');
                        $model->save();
                        return $model;
                    }
                ],
                'delete' => [
                    'class' => 'common\components\rest\DeleteAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'execute' => function ($action, $model) {
                        $params = Yii::$app->getRequest()->getBodyParams();
                        $model = ArticleBiz::find()->andFilterWhere(['id'=>$params['id']])->one();
                        $model->delete();
                        return;
                    }
                ],
            ]
        );
    }

    public function actionOption()
    {
        return [];
    }
}
