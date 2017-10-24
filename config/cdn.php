<?php

return [
    'default' => 'filesystem',
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
        ],
    ],
];
