<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default CDN Generator
    |--------------------------------------------------------------------------
    |
    | This option controls the default CDN driver, and you can select any of
    | the supported drivers.
    |
    | Supported: "filesystem", "qiniu", "alioss"
    |
    */

    'default' => 'filesystem',

    /*
    |--------------------------------------------------------------------------
    | CDN generators
    |--------------------------------------------------------------------------
    |
    | Here, you can define all supported driver configurations, and all
    | configurations can be configured arbitrarily, but the only thing to note
    | is that the "driver" entry must exist.
    |
    */

    'generators' => [
        'filesystem' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\Filesystem::class,
            'disk' => 'public',
            'public' => null,
        ],
        'qiniu' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\Qiniu::class,
            'domain' => null,
            'ak' => null,
            'sk' => null,
            'sign' => false,
            'expires' => 3600,
            'type' => 'cdn', // cdn or object
            'bucket' => null,
        ],
        'alioss' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\AliOss::class,
            'AccessKeyId' => null,
            'AccessKeySecret' => null,
            'bucket' => null,
            'endpoint' => null,
            'ssl' => false,
            'public' => true,
            'expires' => 3600,
        ],
    ],
];
