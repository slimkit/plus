<?php

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
]);
$env = file_get_contents(__DIR__.'/.env.example');
foreach ($connection as $key => $value) {
    $env = preg_replace("/{$key}=(.*)?/i", "{$key}={$value}", $env);
}

file_put_contents(__DIR__.'/.env.travis', $env);
