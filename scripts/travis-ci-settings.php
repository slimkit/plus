#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

$db = $argv[1] ?? 'mysql';
$connection = [];
switch ($db) {
    case 'postgres':
    case 'pgsql':
        $connection = [
            'DB_CONNECTION' => 'pgsql',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '5432',
            'DB_DATABASE' => 'plus',
            'DB_USERNAME' => 'postgres',
            'DB_PASSWORD' => 'postgres',
        ];
        break;

    case 'mysql':
    default:
        $connection = [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_DATABASE' => 'plus',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
        ];
        break;
}

$connection = array_merge($connection, [
    'APP_ENV' => 'testing',
    'APP_DEBUG'=> 'true',
]);

$basePath = dirname(__DIR__);

$env = file_get_contents($basePath.'/storage/configure/.env.example');
foreach ($connection as $key => $value) {
    $env .= PHP_EOL.$key.'='.$value.PHP_EOL;
}

file_put_contents($basePath.'/storage/configure/.env', $env);
