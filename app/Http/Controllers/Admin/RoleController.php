<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\Role;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Get roles.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function roles(Request $request)
    {
        if (! $request->user()->ability('admin:role:show')) {
            return response()->json([
                'errors' => ['你没有管理角色的权限'],
            ])->setStatusCode(403);
        }

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
    public function delete(Request $request, Role $role)
    {
        if (in_array($role->id, [1, 2, 3])) {
            return response()->json([
                'errors' => ['不可删除的用户组'],
            ])->setStatusCode(500);
        }
        if (! $request->user()->ability('admin:role:delete')) {
            return response()->json([
                'errors' => ['你没有删除角色权限'],
            ])->setStatusCode(403);
        }

        if ($role->delete()) {
            return response('', 204);
        }

        return response()->json([
            'errors' => ['删除失败'],
        ])->setStatusCode(500);
    }

    /**
     * 创建角色接口.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createRole(Request $request)
    {
        if (! $request->user()->ability('admin:role:add')) {
            return response()->json([
                'errors' => ['你没有添加角色的权限'],
            ])->setStatusCode(403);
        }

        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        if (! $name) {
            return response()->json([
                'errors' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif (Role::where('name', 'LIKE', $name)->first()) {
            return response()->json([
                'errors' => ['name' => '名称已经存在'],
            ])->setStatusCode(422);
        }

        $role = new Role();
        $role->name = $name;
        $role->display_name = $display_name;
        $role->description = $description;

        if (! $role->save()) {
            return response()->json([
                'errors' => ['保存失败'],
            ])->setStatusCode(400);
        }

        return response()->json($role)->setStatusCode(201);
    }

    /**
     * 完成获取角色接口.
     *
     * @param Request $request
     * @param Role $role
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showRole(Request $request, Role $role)
    {
        if (! $request->user()->ability('admin:role:show')) {
            return response()->json([
                'errors' => ['你没有权限查看角色信息'],
            ])->setStatusCode(403);
        }

        $allabilities = $request->has('all_abilities');
        $hasabilities = $request->has('abilities');

        $abilities = [];
        if ($allabilities === true) {
            $abilities = Ability::all();
        }

        if ($hasabilities === true) {
            $role->load(['abilities']);
        }

        return response()->json([
            'abilities' => $abilities,
            'role' => $role,
        ])->setStatusCode(200);
    }

    /**
     * 更新角色信息.
     *
     * @param Request $request
     * @param Role $role
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateRole(Request $request, Role $role)
    {
        if (! $request->user()->ability('admin:role:update')) {
            return response()->json([
                'errors' => ['你没有权限编辑角色权限'],
            ])->setStatusCode(403);
        }

        $abilities = $request->input('abilities', []);
        $role->abilities()->sync($abilities);

        return response()->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * Gets all Ability nodes.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function abilities(Request $request)
    {
        if (! $request->user()->ability('admin:perm:show')) {
            return response()->json([
                'errors' => ['你没有管理权限节点的权限'],
            ])->setStatusCode(403);
        }

        $abilities = Ability::all();

        return response()->json($abilities)->setStatusCode(200);
    }

    /**
     * 创建权限节点.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createAbility(Request $request)
    {
        if (! $request->user()->ability('admin:perm:add')) {
            return response()->json([
                'errors' => ['你没有权限增加权限节点'],
            ])->setStatusCode(403);
        }

        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        if (! $name) {
            return response()->json([
                'errors' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif (Ability::where('name', 'LIKE', $name)->first()) {
            return response()->json([
                'errors' => ['name' => '名称已经存在'],
            ])->setStatusCode(422);
        }

        $ability = new Ability();
        $ability->name = $name;
        $ability->display_name = $display_name;
        $ability->description = $description;

        if (! $ability->save()) {
            return response()->json([
                'errors' => ['保存失败'],
            ])->setStatusCode(400);
        }

        return response()->json($ability)->setStatusCode(201);
    }

    /**
     * 更新权限节点.
     *
     * @param Request    $request
     * @param Ability $ability
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateAbility(Request $request, Ability $ability)
    {
        if (! $request->user()->ability('admin:perm:update')) {
            return response()->json([
                'errors' => ['你没有修改权限节点的权限'],
            ])->setStatusCode(403);
        }

        $key = $request->input('key');
        $value = $request->input('value');

        if (! in_array($key, ['display_name', 'description'])) {
            return response()->json([
                'errors' => ['请求不合法'],
            ])->setStatusCode(422);
        }

        $ability->$key = $value;

        if (! $ability->save()) {
            return response()->json([
                'errors' => ['数据更新失败'],
            ])->setStatusCode(500);
        }

        return response()->json([
            'messages' => ['更新成功'],
        ])->setStatusCode(201);
    }

    /**
     * 删除权限节点.
     *
     * @param Ability $ability
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleteAbility(Request $request, Ability $ability)
    {
        if (! $request->user()->ability('admin:perm:delete')) {
            return response()->json([
                'errors' => ['你没有权限删除该节点'],
            ])->setStatusCode(403);
        }

        if ($ability->delete()) {
            return response('', 204);
        }

        return response()->json([
            'errors' => ['删除失败'],
        ])->setStatusCode(500);
    }
}
