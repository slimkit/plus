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

use Illuminate\Database\Seeder;
use function Zhiyi\Plus\setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        setting('user')->set([
            'keep-username' => 'root,admin',
            'anonymous' => [
                'status' => true,
                'rule' => '',
            ],
            'invite-template' => '我发现了一个全平台社交系统ThinkSNS+，快来加入吧：http://t.cn/RpFfbbi',
        ]);
        setting('site')->set([
            'gold-switch' => true,
            'reward' => [
                'status' => true,
                'amounts' => '100,500,1000',
            ],
        ]);
        setting('file-storage')->set([
            'default-filesystem' => 'local',
            'channels.public' => [
                'filesystem' => 'local',
            ],
            'task-create-validate' => [
                'image-min-width' => 0,
                'image-max-width' => 2800,
                'image-min-height' => 0,
                'image-max-height' => 2800,
                'file-min-size' => 2048, // 2KB
                'file-max-size' => 2097152, // 2MB
                'file-mime-types' => ['image/png', 'image/jpeg', 'image/gif'],
            ]
        ]);
    }
}
