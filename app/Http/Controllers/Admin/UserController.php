<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Famous;
use Illuminate\Validation\Rule;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Models\UserRecommended;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\EaseMobIm\EaseMobController;
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
        if (! $request->user()->ability('admin:user:show')) {
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
        $perPage = $request->query('perPage', 20);
        $showRole = $request->has('show_role');
        $follow = $request->query('follow', 0);
        $registStartDate = $request->query('regist_start_date');
        $registEndDate = $request->query('regist_end_date');
        $location = array_filter(explode(' ', $request->query('location') ?? ' '));

        $builder = with(new User())->setHidden([])->newQuery();

        $datas = [];
        if ($showRole) {
            $datas['roles'] = Role::all();
        }
        // user id
        if ($userId && $users = $builder->where('id', $userId)->paginate($perPage)) {
            $datas['users'] = $users->map(function ($user) {
                $user->setHidden([]);
                $user->load('recommended');
                $user->load('famous');

                return $user;
            });

            $datas['page']['last_page'] = $users->lastPage();
            $datas['page']['current_page'] = $users->currentPage();
            $datas['page']['total'] = $users->total();

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
            'location' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', end($location)),
                'condition' => boolval(end($location)),
            ],
        ] as $key => $data) {
            if ($data['condition']) {
                $builder->where($key, $data['operator'], $data['value']);
            }
        }

        // 注册时间
        $builder->when(boolval($registStartDate), function ($query) use ($registStartDate) {
            $query->where('created_at', '>=', Carbon::parse($registStartDate)->startOfDay()->toDateTimeString());
        });

        $builder->when(boolval($registEndDate), function ($query) use ($registEndDate) {
            $query->where('created_at', '<=', Carbon::parse($registEndDate)->endOfDay()->toDateTimeString());
        });

        // build sort.
        $builder->orderBy('id', ($sort === 'down' ? 'desc' : 'asc'));

        $role && $builder->whereHas('roles', function ($query) use ($role) {
            $query->where('id', $role);
        });

        $follow && $builder->whereHas('famous', function ($query) use ($follow) {
            // 检索被关注
            if ($follow == 1) {
                $query->where('type', 'like', 'followed');
            }
        });

        $pages = $builder->paginate($perPage);

        $datas['users'] = $pages->map(function ($user) {
            $user->setHidden([]);
            $user->load('recommended');
            $user->load('famous');

            return $user;
        });

        $datas['page']['last_page'] = $pages->lastPage();
        $datas['page']['current_page'] = $pages->currentPage();
        $datas['page']['total'] = $pages->total();

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * 设置注册时关注.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleFamous(Request $request, Famous $famous)
    {
        $user = $request->input('user', 0);
        $type = $request->input('type', 0);

        if (! $user) {
            return response()->json(['message' => '请传递被设置用户'])->setStatusCode(422);
        }

        if (! $type) {
            return response()->json(['message' => '请传递要设置的类型'])->setStatusCode(422);
        }

        $famous->user_id = $user;
        $famous->type = ($type == 1 ? 'followed' : 'each');

        $famous->save();

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }

    /**
     * 取消注册时关注.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleUnFamous(User $user, Famous $famous)
    {
        $f = $famous->where('user_id', '=', $user->id)->first();

        if (! $f) {
            return response()->json(['message' => '当前用户未被设置'])->setStatusCode(404);
        }

        $f->delete();

        return response()->json()->setStatusCode(204);
    }

    /**
     * 后台推荐用户.
     */
    public function recommends(Request $request)
    {
        // $sort = $request->query('sort');
        $userId = $request->query('userId');
        $email = $request->query('email');
        $name = $request->query('name');
        $phone = $request->query('phone');
        // $role = $request->query('role');
        $perPage = $request->query('perPage', 1);
        $showRole = $request->has('show_role');
        $datas = [
            'page' => [],
            'roles' => '',
            'lastPage' => 0,
            'perPage' => $perPage,
            'total' => 0,
        ];

        if ($showRole) {
            $datas['roles'] = Role::all();
        }

        // user id
        if ($userId && $users = UserRecommended::where('user_id', $userId)->paginate($perPage)) {
            $datas['page'] = $users->map(function ($user) {
                $user->setHidden([]);
                $user->load('user');

                return $user->user;
            });

            return response()->json($datas)->setStatusCode(200);
        }

        $sourceUsers = [];
        if ($name || $email || $phone) {
            $sourceUsers = User::when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($email, function ($query) use ($email) {
                return $query->where('email', '=', $email);
            })
            ->when($phone, function ($query) use ($phone) {
                return $query->where('phone', 'like', "%{$phone}%");
            })
            ->select('id')
            ->get()
            ->pluck('id');
        }

        $users = UserRecommended::with('user')
            ->when($sourceUsers, function ($query) use ($sourceUsers) {
                return $query->whereIn('user_id', $sourceUsers);
            })
            ->paginate($perPage);

        $list = $users->getCollection();

        $datas['page'] = $list->map(function ($user) {
            $user->user->setHidden([]);

            return $user->user;
        });

        $datas['lastPage'] = $users->lastPage();
        $datas['perPage'] = $perPage;
        $datas['total'] = $users->total();
        $datas['currentPage'] = $users->currentPage();

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return mixed
     */
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
                'nullable',
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
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'roles.required' => '必须选择用户组',
            'roles.array' => '发送数据格式错误',
            'roles.in' => '选择的用户组中存在不合法信息',
        ];

        $this->validate($request, $rules, $messages);

        foreach ($request->only(['email', 'name', 'phone']) as $key => $value) {
            $user->$key = $value ?: null;
        }
        $oldPwdHash = $user->getImPwdHash();
        if ($password = $request->input('password')) {
            $user->createPassword($password);
        }

        $easeMob = new EaseMobController();

        $response = app('db.connection')->transaction(function () use ($user, $request, $easeMob, $oldPwdHash) {
            $user->save();
            $user->roles()->sync(
                $request->input('roles')
            );

            // 环信重置密码
            $request->user_id = $user->id;
            $request->old_pwd_hash = $oldPwdHash;
            $im = $easeMob->resetPassword($request);
            if ($im->getStatusCode() != 201) {
                return false;
            }

            return true;
        });

        return response()->json([
            'messages' => [
                $response === true ? '更新成功' : '更新失败',
            ],
        ])->setStatusCode($response === true ? 201 : 422);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $rules = [
            'phone' => 'nullable|cn_phone|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'name' => 'required|username|min:2|max:12|unique:users,name',
            'password' => 'required',
        ];
        $messages = [
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'email.email' => '请输入正确的 E-Mail 格式',
            'email.unique' => '邮箱已经存在',
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
        $user->email = $request->input('email');
        $user->createPassword($request->input('password'));

        if ($user->save()) {

            // 环信用户注册
            $easeMob = new EaseMobController();
            $request->user_id = $user->id;
            $im = $easeMob->createUser($request);
            if ($im->getStatusCode() != 201) {
                return response()->json([
                    'message' => ['环信用户注册失败'],
                ])->setStatusCode(400);
            }

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
        if (! $request->user()->ability('admin:user:delete')) {
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
        if (! $request->user()->ability('admin:user:show')) {
            return response()->json([
                'errors' => ['你没有权限执行该操作'],
            ])->setStatusCode(403);
        }

        $showRole = $request->query('show_role');

        $user->load(['roles']);
        $user->setHidden([]);

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

    /**
     * 增加推荐用户.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleRecommend(Request $request, UserRecommended $recommend)
    {
        $user = $request->input('user', 0);

        if (! $user) {
            return response()->json(['message' => '未指定要被推荐的用户'])->setStatusCode(422);
        }

        $recommend->user_id = $user;
        $recommend->save();

        return response()->json(['message' => '推荐成功'])->setStatusCode(201);
    }

    /**
     * 取消用户推荐.
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function handleUnRecommend(User $user, UserRecommended $recommend)
    {
        $user = $recommend->where('user_id', '=', $user->id)->first();
        if (! $user) {
            return response()->json(['message' => '该用户未被推荐'])->setStatusCode(404);
        }

        $user->delete();

        return response()->json()->setStatusCode(204);
    }

    /**
     * 注册配置，暂时存放于配置文件.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateRegisterSetting(Request $request, Configuration $config)
    {
        $conf = $request->only(['showTerms', 'method', 'content', 'fixed', 'type']);

        $settings = [];
        foreach ($conf as $key => $value) {
            $settings['registerSettings.'.$key] = $value;
        }

        $config->set($settings);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }

    /**
     * 获取注册配置.
     * @return [type] [description]
     */
    public function getRegisterSetting(Repository $con, Configuration $config)
    {
        $conf = $con->get('registerSettings');

        if (is_null($conf)) {
            $conf = $this->initRegisterConfiguration($config);
        }

        return response()->json($conf)->setStatusCode(200);
    }

    public function initRegisterConfiguration(Configuration $config_model)
    {
        $config = $config_model->getConfiguration();

        $config->set('registerSettings.showTerms', 'open');
        $config->set('registerSettings.method', 'all');
        $config->set('registerSettings.fixed', 'need');
        $config->set('registerSettings.type', 'all');
        $config->set('registerSettings.content', '# 服务条款及隐私政策');

        // $configuration->save($config);

        return $config['registerSettings'];
    }
}
