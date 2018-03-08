<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    /*alidayu sms service*/
    'sms.config'=>[
        'app_key'    => '12345678',
        'app_secret' => 'abcdefghijklmnopqrstuvwxyz123456',
    ],
    'response.code' => [
        /*成功*/
        'success'                   => ['id'=>0,        'name'=>'SUCCESS',              'label'=>'请求成功'],

        /*参数，自定义*/
        'param_invalid'             => ['id'=>1,        'name'=>'INVALID_PARAMETER',    'label'=>'请求参数错误'],

        /*系统*/
        'system_illegal_action'     => ['id'=>1000,     'name'=>'ILLEGAL_ACTION',       'label'=>'非法操作'],
        'system_busy'               => ['id'=>1001,     'name'=>'SYSTEM_BUSY',          'label'=>'服务器繁忙，请稍候再试'],

        /*用户态*/
        'user_not_login'            => ['id'=>2000,     'name'=>'USER_NOT_LOGIN',       'label'=>'用户未登录'],
        'user_frozen'               => ['id'=>2001,     'name'=>'USER_FROZEN',          'label'=>'账户被冻结'],
    ],
    'cache.prefix' => 'cache.',
    'queue.prefix' => 'queue.',
];
