<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Permission;
use Zhiyi\Plus\Models\Role;

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

    /**
     * 被禁用的用户.
     * @return [type] [description]
     */
    protected function createDisabledRole()
    {
        $role = Role::create([
            'name' => 'disabler',
            'display_name' => '禁用用户',
            'description' => '被禁止登录用户， 需要手动设置',
        ]);
    }
}
