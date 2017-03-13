<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\Permission;

class RoleController extends Controller
{
    /**
     * Get roles.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function roles()
    {
        $roles = Role::all();

        return response()->json($roles)->setStatusCode(200);
    }

    /**
     * 删除用户组.
     *
     * @param Role $role
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Role $role)
    {
        if ($role->delete()) {
            return response('', 204);
        }

        return response()->json([
            'errors' => ['删除失败'],
        ])->setStatusCode(500);
    }

    /**
     * 获取全部权限节点.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function perms()
    {
        $perms = Permission::all();

        return response()->json($perms)->setStatusCode(200);
    }
}
