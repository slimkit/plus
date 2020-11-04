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

namespace SlimKit\PlusCheckIn\Seeds;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Models\Role;

class AbilitySeeder extends Seeder
{
    /**
     * Run Abilitys Node Insert Data Method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $ability = Ability::create([
            'name' => 'admin: checkin config',
            'display_name' => '签到管理',
            'description' => '用户是否拥有后台管理签到权限',
        ]);

        $roles = Role::whereIn('name', ['founder'])->get();
        $roles->each(function (Role $role) use ($ability) {
            $role->abilities()->syncWithoutDetaching($ability);
        });
    }
}
