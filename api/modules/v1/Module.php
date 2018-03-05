<?php
namespace api\modules\v1;

use Yii;
use common\components\auth\QueryParamAuth;
use common\components\auth\CompositeAuth;

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
                QueryParamAuth::className(),
            ],
            'optional' => [
                'common/*',
                'index/*',
                'article/*',
                'user/login',
            ],
        ];
        return $behaviors;
    }


}