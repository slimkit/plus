<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Driver select.
    |--------------------------------------------------------------------------
    |
    | ThinkSNS + through the PHP design SMS send function as a driver to send
    | text messages. Your program uses "testing" to send text messages by default.
    |
    | Supported: "testing",
    |
    */

    'default' => 'testing',

    /*
    |--------------------------------------------------------------------------
    | SMS Driver.
    |--------------------------------------------------------------------------
    |
    | All driver list.
    |
    */

    'driver' => [
        'testing' => '只记录（不通过任何驱动发送）',
        'alidayu' => '阿里大于',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Connections
    |--------------------------------------------------------------------------
    |
    | Here you can define the connection to send text messages, each available
    | transmission methods are defined here.
    |
    */

    'connections' => [
        'alidayu' => [
            'app_key' => null,
            'app_secret' => null,
            'sandbox' => false,
            'sign_name' => null,
            'verify_template_id' => null,
        ],
    ],
];
