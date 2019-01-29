<?php

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

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\Ability;
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
        // 权限节点.
        $this->call(AbilitySeeder::class);

        // roles
        $this->createFounderRole();
        $this->createOwnerRole();
        $this->createDisabledRole();
    }

    /**
     * 创始人角色.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createFounderRole()
    {
        $role = Role::create([
            'name' => 'founder',
            'display_name' => '创始人',
            'description' => '站点创始人',
            'non_delete' => 1,
        ]);

        $abilities = Ability::all();

        $role->abilities()->sync($abilities);
    }

    /**
     * 业主，普通用户角色.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createOwnerRole()
    {
        $role = Role::create([
            'name' => 'owner',
            'display_name' => '普通用户',
            'description' => '普通用户',
        ]);

        $abilities = Ability::where('name', 'not like', 'admin:%')->get();

        $role->abilities()->sync($abilities);
    }

    /**
     * 被禁用的用户.
     * @return [type] [description]
     */
    protected function createDisabledRole()
    {
        Role::create([
            'name' => 'disabler',
            'display_name' => '禁用用户',
            'description' => '被禁止登录用户， 需要手动设置',
            'non_delete' => 1,
        ]);
    }
}
