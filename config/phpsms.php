<?php

/*
 * config file for PhpSms
 */
return [

    /*
     * agent use scheme
     * -------------------------------------------------------------------
     * Format: 'name' => scheme
     *
     * The scheme value include:
     * 1. weight (must be a positive integer)
     * 2. 'backup' (ignore upper/lower case)
     *
     * supported agents:
     * 'Luosimao', 'YunTongXun', 'YunPian', 'SubMail', 'Ucpaas', 'JuHe', 'Alidayu', 'Log'
     */
    'scheme' => [
        'Log',
    ],

    /*
     * agents config
     * -------------------------------------------------------------------
     * Note: agent name must be string.
     *
     */
    'agents' => [

        /*
         * -----------------------------------
         * YunPian
         * 云片代理器
         * -----------------------------------
         * website:http://www.yunpian.com
         * support content sms.
         */
        'YunPian' => [
            //用户唯一标识，必须
            'apikey' => 'your api key',
        ],

        /*
         * -----------------------------------
         * YunTongXun
         * 云通讯代理器
         * -----------------------------------
         * website：http://www.yuntongxun.com/
         * support template sms.
         */
        'YunTongXun' => [
            //主帐号,对应开官网发者主账号下的 ACCOUNT SID
            'accountSid' => 'your account sid',

            //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
            'accountToken' => 'your account token',

            //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
            //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
            'appId' => 'your app id',

            //请求地址
            //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
            //生产环境（用户应用上线使用）：app.cloopen.com
            'serverIP' => 'app.cloopen.com',

            //请求端口，生产环境和沙盒环境一致
            'serverPort' => '8883',

            //REST版本号，在官网文档REST介绍中获得。
            'softVersion' => '2013-12-26',

            //被叫号显
            'displayNum' => null,

            //语音验证码使用的语言类型
            'voiceLang' => 'zh',

            //语音验证码播放次数
            'playTimes' => 3,
        ],

        /*
         * -----------------------------------
         * SubMail
         * -----------------------------------
         * website:http://submail.cn/
         * support template sms.
         */
        'SubMail' => [

            'appid' => 'your app id',

            'signature' => 'your app key',
        ],

        /*
         * -----------------------------------
         * luosimao
         * -----------------------------------
         * website:http://luosimao.com
         * support content sms.
         */
        'Luosimao' => [
            // 短信 API key
            // 在管理中心->短信->触发发送下查看
            'apikey' => 'your api key',

            // 语言验证 API key
            // 在管理中心->语音->语音验证下查看
            'voiceApikey' => 'your voice api key',
        ],

        /*
         * -----------------------------------
         * ucpaas
         * -----------------------------------
         * website:http://ucpaas.com
         * support template sms.
         */
        'Ucpaas' => [
            //主帐号,对应开官网发者主账号下的 ACCOUNT SID
            'accountSid' => 'your account sid',

            //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
            'accountToken' => 'your account token',

            //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
            //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
            'appId' => 'your app id',
        ],

        /*
         * -----------------------------------
         * JuHe
         * 聚合数据
         * -----------------------------------
         * website:https://www.juhe.cn
         * support template sms.
         */
        'JuHe' => [
            //应用App Key
            'key' => 'your key',

            //语音验证码播放次数
            'times' => 3,
        ],

        /*
         * -----------------------------------
         * Alidayu
         * 阿里大鱼代理器
         * -----------------------------------
         * website:http://www.alidayu.com
         * support template sms.
         */
        'Alidayu' => [
            //请求地址
            'sendUrl' => 'https://eco.taobao.com/router/rest',

            //淘宝开放平台中，对应阿里大鱼短信应用的App Key
            'appKey' => env('SMS_ALIDAYU_APP_KEY', null),

            //淘宝开放平台中，对应阿里大鱼短信应用的App Secret
            'secretKey' => env('SMS_ALIDAYU_SECRET_KEY', null),

            //短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名
            'smsFreeSignName' => env('SMS_ALIDAYU_FREE_SIGN_NAME', 'ThinkSNS+'),

            //被叫号显(用于语音通知)，传入的显示号码必须是阿里大鱼“管理中心-号码管理”中申请或购买的号码
            'calledShowNum' => 'your called show num',
        ],

        /*
         * -----------------------------------
         * SendCloud
         * -----------------------------------
         * website: http://sendcloud.sohu.com/sms/
         * support template sms.
         */
        'SendCloud' => [
            //SMS_USER
            'smsUser' => 'your SMS_USER',

            //SMS_KEY
            'smsKey'  => 'your SMS_KEY',
        ],
    ],
];
