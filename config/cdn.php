<?php

return [
    'default' => 'local',
    'generators' => [
        'local' => [
            'driver' => \Zhiyi\Plus\Cdn\Adapter\Local::class,
        ],
    ],
];
