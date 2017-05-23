<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;

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
                Rule::in(Role::all()->keyBy('id')->keys()->toArray()),
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
            'name.unique' => '用户名已经被其他用户所使用',
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

    public function store(Request $request)
    {
        $rules = [
            'phone' => 'required|cn_phone|unique:users,phone',
            'name' => 'required|username|min:2|max:12|unique:users,name',
            'password' => 'required',
        ];
        $messages = [
            'phone.required' => '请输入用户手机号码',
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'name.required' => '请输入用户名',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.min' => '用户名最少输入两个字',
            'name.max' => '用户名最多输入十二个字',
            'name.unique' => '用户名已经被其他用户所使用',
            'password.required' => '请输入密码',
        ];
        $this->validate($request, $rules, $messages);

        $user = new User();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->createPassword($request->input('password'));

        if ($user->save()) {
            return response()->json([
                'message' => ['成功'],
                'user_id' => $user->id,
            ])->setStatusCode(201);
        }

        return response()->json([
            'message' => ['添加失败'],
        ])->setStatusCode(400);
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
     * 获取用户信息设置.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showSetting()
    {
        $roles = Role::all();
        $currentRole = CommonConfig::byNamespace('user')
            ->byName('default_role')
            ->value('value');

        return response()
            ->json([
                'roles' => $roles,
                'current_role' => $currentRole,
            ])
            ->setStatusCode(200);
    }

    /**
     * 储存用户基本设置.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function storeSetting(Request $request)
    {
        $rules = [
            'role' => 'required|exists:roles,id',
        ];
        $messages = [
            'role.required' => '必须选择用户组',
            'role.exists' => '选择的用户组中存在不合法信息',
        ];
        $this->validate($request, $rules, $messages);

        $role = $request->input('role');

        CommonConfig::updateOrCreate(
            ['namespace' => 'user', 'name' => 'default_role'],
            ['value' => $role]
        );

        return response()
            ->json([
                'message' => ['更新成功!'],
            ])
            ->setStatusCode(201);
    }
}
