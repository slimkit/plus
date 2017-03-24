<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
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

    /**
     * 创建用户.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createUser(Request $request)
    {
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
     * 删除用户
     *
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleteUser(User $user)
    {
        $user->delete();

        return response('', 204);
    }
}
