<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP 请求的超时时间
    |--------------------------------------------------------------------------
    |
    | 设置 HTTP 请求超时时间，单位为「秒」。可以为 int 或者 float。
    |
    */

    'timeout' => 5.0,

    /*
    |--------------------------------------------------------------------------
    | 默认发送配置
    |--------------------------------------------------------------------------
    |
    | strategy 为策略器，默认使用「顺序策略器」，可选值有：
    |       - \Overtrue\EasySms\Strategies\OrderStrategy::class  顺序策略器
    |       - \Overtrue\EasySms\Strategies\RandomStrategy::class 随机策略器
    |
    | gateways 设置可用的发送网关，可用网关：
    |       - aliyun 阿里云信
    |       - alidayu 阿里大于
    |       - yunpian 云片
    |       - submail Submail
    |       - luosimao 螺丝帽
    |       - yuntongxun 容联云通讯
    |       - huyi 互亿无线
    |       - juhe 聚合数据
    |       - sendcloud SendCloud
    |       - baidu 百度云
    |
    */

    'default' => [
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
        'gateways' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | 发送网关配置
    |--------------------------------------------------------------------------
    |
    | 可用的发送网关，基于网关列表，这里配置可用的发送网关必要的数据信息。
    |
    */

    'gateways' => [

        // 阿里大于
        'alidayu' => [
            'app_key' => null,
            'app_secret' => null,
            'sign_name' => null,
        ],

        // 阿里云
        'aliyun' => [
            'access_key_id' => null,
            'access_key_secret' => null,
            'sign_name' => null,
        ],

        // 云片
        'yunpian' => [
            'api_key' => null,
        ],
    ],

    'gateway_aliases' => [
        'aliyun' => \Overtrue\EasySms\Gateways\AliyunGateway::class,
        'alidayu' => \Overtrue\EasySms\Gateways\AlidayuGateway::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | 消息支持频道
    |--------------------------------------------------------------------------
    |
    | 发送消息可用根据不同频道配置注入不同频道配置数据。
    |
    */

    'channels' => [

        // 验证码频道
        'code' => [
            'alidayu' => [
                'template' => null,
                ':code' => 'code',
            ],
            'aliyun' => [
                'template' => null,
                ':code' => 'code',
            ],
            'yunpian' => [
                'content' => null,
            ],
        ],
    ],
];
