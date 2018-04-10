<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'name' => 'application-backend',
            'timeout' => 3600,
            'keyPrefix' => 'backend.session.',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
                'password' => 'pass',
            ]
        ],
    ],
];
