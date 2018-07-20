#!/usr/bin/env php
<?php

declare(strict_types=1);


$env = [
	'APP_ENV' => 'testing',
	'APP_DEBUG' => true,
	'DB_CONNECTION' => 'mysql',
	'DB_HOST' => '127.0.0.1',
	'DB_PORT' => 3306,
	'DB_USERNAME' => 'root',
	'DB_PASSWORD' => null,
	'DB_DATABASE' => 'plus',
];

$basePath = dirname(__DIR__);
$envContents = file_get_contents($basePath.'/storage/configure/.env.example');
foreach ($env as $key => $value) {
    $envContents .= PHP_EOL.$key.'='.$value.PHP_EOL;
}

file_put_contents($basePath.'/storage/configure/.env', $envContents);
