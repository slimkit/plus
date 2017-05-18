<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Exception;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Zhiyi\Plus\Http\Middleware\V1\VerifyPhoneNumber;
use Zhiyi\Plus\Http\Middleware\V1\VerifyUserNameRole;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * 获取用户列表数据.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function users(Request $request)
    {
        if (! $request->user()->can('admin:user:show')) {
            return response()->json([
                'errors' => ['你没有权限查管理用户'],
            ])->setStatusCode(403);
        }

        $sort = $request->query('sort');
        $userId = $request->query('userId');
        $email = $request->query('email');
        $name = $request->query('name');
        $phone = $request->query('phone');
        $role = $request->query('role');
        $perPage = $request->query('perPage', 10);
        $showRole = $request->has('show_role');

        $builder = with(new User())->newQuery();

        $datas = [];
        if ($showRole) {
            $datas['roles'] = Role::all();
        }

        // user id
        if ($userId && $users = $builder->where('id', $userId)->paginate($perPage)) {
            $datas['page'] = $users;

            return response()->json($datas)->setStatusCode(200);
        }

        foreach ([
            'email' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $email),
                'condition' => boolval($email),
            ],
            'name' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $name),
                'condition' => boolval($name),
            ],
            'phone' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $phone),
                'condition' => boolval($phone),
            ],
        ] as $key => $data) {
            if ($data['condition']) {
                $builder->where($key, $data['operator'], $data['value']);
            }
        }

        // build sort.
        $builder->orderBy('id', ($sort === 'down' ? 'desc' : 'asc'));

        $role && $builder->whereHas('roles', function ($query) use ($role) {
            $query->where('id', $role);
        });

        $datas['page'] = $builder->paginate($perPage);

        return response()->json($datas)->setStatusCode(200);
    }

    public function update(Request $request, User $user)
    {
        // 验证规则.
        $rules = [
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'name' => [
                'required',
                'username',
                'min:2',
                'max:12',
                Rule::unique('users', 'name')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'cn_phone',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'roles' => [
                'required',
                'array',
                Rule::in(Role::all()->keyBy('id')->keys()->toArray())
            ],
        ];

        // 消息
        $messages = [
            'email.email' => '请输入正确的 E-Mail 格式',
            'email.unique' => '邮箱已经存在',
            'name.required' => '请输入用户名',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.min' => '用户名最少输入两个字',
            'name.max' => '用户名最多输入十二个字',
            'name.unique' => '用户名以存在',
            'phone.required' => '请输入用户手机号码',
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'roles.required' => '必须选择用户组',
            'roles.array' => '发送数据格式错误',
            'roles.in' => '选择的用户组中存在不合法信息',
        ];

        $this->validate($request, $rules, $messages);

        foreach ($request->only(['email', 'name', 'phone']) as $key => $value) {
            if ($value) {
                $user->$key = $value;
            }
        }

        if ($password = $request->input('password')) {
            $user->createPassword($password);
        }

        $response = app('db.connection')->transaction(function () use ($user, $request) {
            $user->save();
            $user->roles()->sync(
                $request->input('roles')
            );

            return true;
        });

        return response()->json([
            'messages' => [
                $response === true ? '更新成功' : '更新失败',
            ],
        ])->setStatusCode($response === true ? 201 : 422);
    }

    /**
     * 创建用户.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUser(Request $request)
    {
        if (! $request->user()->can('admin:user:add')) {
            return response()->json([
                'errors' => ['你没有添加用户权限'],
            ])->setStatusCode(403);
        }

        $name = $request->input('name');
        $phone = $request->input('phone');
        $password = $request->input('password');

        $user = new User();
        $user->name = $name;
        $user->phone = $phone;
        $user->createPassword($password);

        if (! $user->save()) {
            return response()->json([
                'errors' => ['添加失败'],
            ])->setStatusCode(400);
        }

        return response()->json([
            'message' => '成功',
            'user_id' => $user->id,
        ])->setStatusCode(201);
    }

    /**
     * 删除用户.
     *
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleteUser(Request $request, User $user)
    {
        if (! $request->user()->can('admin:user:delete')) {
            return response()->json([
                'errors' => ['你没有删除用户的权限'],
            ])->setStatusCode(403);
        }

        $user->delete();

        return response('', 204);
    }

    /**
     * 获取用户资料.
     *
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showUser(Request $request, User $user)
    {
        if (! $request->user()->can('admin:user:show')) {
            return response()->json([
                'errors' => ['你没有权限执行该操作'],
            ])->setStatusCode(403);
        }

        $showRole = $request->query('show_role');

        $user->load(['roles']);

        $data = [
            'user' => $user,
            'roles' => $showRole ? Role::all() : [],
        ];

        return response()->json($data)->setStatusCode(200);
    }


    /**
     * 用于执行部分更新验证，非按照要求抛出异常.
     *
     * @param [type] $mixed [description]
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function throwResponseError($mixed)
    {
        if ($mixed instanceof JsonResponse) {
            $data = $mixed->getData();
            throw new Exception($data->message ?? '更新失败', 422);
        } elseif (! $mixed instanceof User) {
            throw new Exception('更新失败', 422);
        }
    }

    /**
     * 同步用户角色.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function syncUserRoles(Request $request, User $user)
    {
        $roles = $request->input('roles', []);

        if (! is_array($roles)) {
            throw new Exception('上传角色数据错误', 422);
        } elseif (count($roles) < 1) {
            throw new Exception('请选择用户角色', 422);
        }

        $user->roles()->sync($roles);

        return $user;
    }

    /**
     * 更新用户密码
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function updateUserPassword(Request $request, User $user)
    {
        $password = $request->input('password');

        if ($password) {
            $user->createPassword($password);
        }

        return $user;
    }

    /**
     * 更新用户邮箱.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function updateUserEmail(Request $request, User $user)
    {
        $email = $request->input('email');

        if (! $email || $email === $user->email) {
            return $user;
        }

        try {
            $this->validate($request, [
                'email' => 'email',
            ]);
        } catch (ValidationException $e) {
            throw new Exception('E-Mail 格式不正确', 422);
        }

        $theUser = User::byEmail($email)->withTrashed()->first();

        if ($theUser && $user->id !== $theUser->id) {
            throw new Exception('Email 已经被其他用户所使用', 422);
        }

        $user->email = $email;

        return $user;
    }

    /**
     * 更新用户手机号码.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function updateUserPhone(Request $request, User $user)
    {
        $phone = $request->input('phone');

        if (! $phone || $phone === $user->phone) {
            return $user;
        }

        return app(VerifyPhoneNumber::class)->handle($request, function () use ($user, $phone) {
            $theUser = User::byPhone($phone)->withTrashed()->first();

            if ($theUser && $user->id !== $theUser->id) {
                throw new Exception('手机号已经被其他用户所使用', 422);
            }

            $user->phone = $phone;

            return $user;
        });
    }

    /**
     * 修改用户名称.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function updateUsername(Request $request, User $user)
    {
        $name = $request->input('name');
        if (! $name || $name === $user->name) {
            return $user;
        }

        return app(VerifyUserNameRole::class)->handle($request, function () use ($user, $name) {
            $theUser = User::byName($name)->withTrashed()->first();

            if ($theUser && $user->id !== $theUser->id) {
                throw new Exception('用户名已经被其他用户所使用', 422);
            }

            $user->name = $name;

            return $user;
        });
    }
}
