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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->createFounderUser();
    }

    /**
     * Insert the founder information.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createFounderUser()
    {
        $user = User::query()->create(['name' => 'root', 'password' => bcrypt('root')]);
        $user->roles()->sync(
            Role::where('non_delete', 1)->get()
        );
    }
}
