<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Permission;
use Zhiyi\Plus\Models\Role;

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
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function perms()
    {
        $perms = Permission::all();

        return response()->json($perms)->setStatusCode(200);
    }

    /**
     * 更新权限节点.
     *
     * @param Request    $request
     * @param Permission $perm
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updatePerm(Request $request, Permission $perm)
    {
        $key = $request->input('key');
        $value = $request->input('value');

        if (!in_array($key, ['display_name', 'description'])) {
            return response()->json([
                'errors' => ['请求不合法'],
            ])->setStatusCode(422);
        }

        $perm->$key = $value;

        if (!$perm->save()) {
            return response()->json([
                'errors' => ['数据更新失败'],
            ])->setStatusCode(500);
        }

        return response()->json([
            'messages' => ['更新成功'],
        ])->setStatusCode(201);
    }
}
