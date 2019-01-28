<?php
namespace api\modules\v1\controllers;

use Yii;
use common\components\rest\ActiveController;
use common\components\response\ReturnMsg;
use common\components\tools\ParamValidator;
use common\modelsBiz\ArticleBiz;

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
                        return $model;
                    }
                ],
            ]
        );
    }

    public function actionAdd()
    {
        //param validator
        $validator = new ParamValidator();
        $paramConfig = [];
        $paramConfig[] = $validator::stringParam('title',array('title'=>'文章标题'));
        $paramConfig[] = $validator::stringParam('content',array('title'=>'文章内容'));
        if(!$params = $validator->validateParams($paramConfig)){
            return $validator->getError();
        }

        ArticleBiz::createArticle($params);
        return ReturnMsg::success('保存成功');
    }

    public function actionParamsValidate()
    {
        //param validator
        $validator = new ParamValidator();
        $paramConfig = [];
        $paramConfig[] = $validator::numberParam('phone',array('title'=>'手机号码'));
        if(!$params = $validator->validateParams($paramConfig)){
            return $validator->getError();
        }
        return ReturnMsg::success('验证成功');
        //return ReturnMsg::fail('验证失败！','sex');
    }
}