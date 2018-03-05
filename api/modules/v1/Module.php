<?php
namespace api\modules\v1;

use Yii;
use common\components\tools\Auth;
use common\components\tools\CompositeAuth;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';
    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //验证access-token
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                Auth::className(),
            ],
            'optional' => [
                'common/*',
                'index/*',
                'article/*',
            ],
        ];
        return $behaviors;
    }


}