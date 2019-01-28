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
        'param_invalid'             => ['id'=>1001,        'name'=>'INVALID_PARAMETER',    'label'=>'请求参数错误'],

        /*逻辑错误*/
        'logic_invalid'             => ['id'=>1002,        'name'=>'INVALID_Logic',        'label'=>'逻辑错误'],

        /*系统*/
        'system_illegal_action'     => ['id'=>2001,     'name'=>'ILLEGAL_ACTION',       'label'=>'非法操作'],
        'system_busy'               => ['id'=>2002,     'name'=>'SYSTEM_BUSY',          'label'=>'服务器繁忙，请稍候再试'],

        /*用户态*/
        'user_not_login'            => ['id'=>3001,     'name'=>'USER_NOT_LOGIN',       'label'=>'身份异常请重新登录'],
        'user_frozen'               => ['id'=>3002,     'name'=>'USER_FROZEN',          'label'=>'账户被冻结'],

        /*系统错误*/
        'system_error'              => ['id'=>5000,     'name'=>'SYSTEM_ERROR',         'label'=>'系统出错'],
    ],
    'cache.prefix' => 'cache.',
    'queue.prefix' => 'queue.',
];
