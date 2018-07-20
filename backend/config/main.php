<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    /*$response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];*/
                    $response->statusCode = 200;
                }
            }
        ],
        'user' => [
            'identityClass' => 'backend\models\AdminUser',
            'enableAutoLogin' => true,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'error' => [
            'class' => '\common\components\exception\Error',
        ],
        'errorHandler' => [
            'class' => 'common\components\exception\ErrorHandler',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'admin',
                    'controller' => [
                        'v1/article',
                    ],
                    'extraPatterns' => [
                        'POST {id}' => 'update',
                        'DELETE {id}' => 'delete',
                        'GET {id}' => 'view',
                        'POST' => 'create',
                        'GET' => 'index',
                    ],
                ],
                //common route
                'admin/<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
    'as access' => [
        'class' => 'common\components\AccessControl',
        'allowActions' => [
            'v1/user/login',//允许所有人访问admin节点及其子节点
        ]
    ],
    'params' => $params,
];
