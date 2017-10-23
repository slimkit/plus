<?php

return [
    'default' => 'local',
    'generators' => [
        'local' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\Local::class,
        ],
        'qiniu' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\Qiniu::class,
            'domain' => null,
            'ak' => null,
            'sk' => null,
            'sign' => false,
            'expires' => 3600,
        ],
    ],
];
