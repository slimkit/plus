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

use Zhiyi\Plus\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * 开始运行 seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        foreach ([
            'founder' => [
                'display_name' => '创始人',
                'description' => '站点创始人',
                'non_delete' => 1,
            ],
            'disabler' => [
                'display_name' => '禁用用户',
                'description' => '被禁止登录用户， 需要手动设置',
                'non_delete' => 1,
            ],
            'developer' => [
                'display_name' => '开发/运维人员',
                'description' => '用于访问调试监控工具的专属角色',
                'non_delete' => 1,
            ],
            'owner' => [
                'name' => 'owner',
                'display_name' => '普通用户',
                'description' => '普通用户',
            ],
        ] as $name => $more) {
            Role::firstOrCreate(['name' => $name], $more);
        }
        // 权限节点.
        $this->call(AbilitySeeder::class);
    }
}
