<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module'
        ],
    ],
    'controllerNamespace' => 'api\controllers',
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
            'identityClass' => 'common\modelsBiz\UserBiz',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
            'timeout' => 0,
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
                    'controller' => [
                        'v1/article2',
                        'v1/center',
                    ]
                ],
                //路由
                'GET api/v1/article' => 'v1/article/index',
                'POST api/v1/article' => 'v1/article/create',
                'GET api/v1/article/<id:\d+>' => 'v1/article/view',
                'POST api/v1/article/params-validate' => 'v1/article/params-validate',
                'POST api/v1/article/add' => 'v1/article/add',

                'POST api/v1/user/login' => 'v1/user/login',
                'GET api/v1/user/is-login' => 'v1/user/is-login',
                'POST api/v1/user/logout' => 'v1/user/logout',
            ],
        ],
    ],
    'params' => $params,
];
