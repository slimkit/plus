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

namespace Slimkit\Feed\Database\Seeders;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Models\Role;

class AbilityTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        $ability = new Ability();
        $ability->name = 'feed-post';
        $ability->display_name = '发送分享';
        $ability->description = '用户发送分享权限';
        $ability->save();

        $roles = Role::whereIn('name', ['founder', 'owner'])->get();
        $roles->each(function (Role $role) use ($ability) {
            $role->abilities()->syncWithoutDetaching($ability);
        });
    }
}
