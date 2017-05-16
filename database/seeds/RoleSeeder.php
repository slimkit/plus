<?php

use Zhiyi\Plus\Models\Role;
use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Permission;

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
        $this->call(PermissionSeeder::class);

        // roles
        $this->createFounderRole();
        $this->createOwnerRole();
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
        ]);

        $perms = Permission::all();

        $role->perms()->sync($perms);
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

        $perms = Permission::where('name', 'not like', 'admin:%')->get();

        $role->perms()->sync($perms);
    }
}
