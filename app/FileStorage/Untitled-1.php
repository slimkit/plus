<?php

return [
    'default-filsystem' => 'local',
    'filesystems' => [
        'local' => [
            'disk' => 'local'
        ],
        'aliyun-oss' => [
            'bucket' => null,
            'access-key-id' => null,
            'access-key-secret' => null,
            'domain' => null,
            'timeout' => 3360,
            'acl' => 'public-read', // public-read-write、public-read、private
        ],
    ],
    'channels' => [
        'public' => [
            'filesystem' => ''
        ],
        'protected' => [
            'filesystem' => ''
        ],
        'private' => [
            'filesystem' => ''
        ],
    ]
];
