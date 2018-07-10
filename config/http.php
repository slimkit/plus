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
    'cors' => [
        'credentials' => false,
        'origin' => ['*'],
        'methods' => ['*'],       // => ['GET', 'POST', 'PATCH', 'PUT', 'OPTION', 'PUT', 'DELETE']
        'allow-headers' => ['*'], // => ['Origin', 'Content-Type', 'Accept', 'X-Requested-With']
        'expose-headers' => [],
        'max-age' => 0,
    ],
    'spa' => [
        'open' => false,
        'uri' => null,
    ],
    'web' => [
        'open' => false,
        'uri' => null,
    ],
];
