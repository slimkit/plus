<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Exception;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Zhiyi\Plus\Http\Middleware\VerifyPhoneNumber;
use Zhiyi\Plus\Http\Middleware\VerifyUserNameRole;

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
     * 删除用户.
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

    /**
     * 获取用户资料.
     *
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showUser(User $user)
    {
        $data = [
            'user' => $user,
        ];

        return response()->json($data)->setStatusCode(200);
    }

    /**
     * 更新用户资料.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateUser(Request $request, User $user)
    {
        try {
            $this->throwResponseError($user = $this->updateUsername($request, $user));
            $this->throwResponseError($user = $this->updateUserPhone($request, $user));
            $this->throwResponseError($user = $this->updateUserEmail($request, $user));

            if (! $user->save()) {
                throw new Exception('更新失败', 400);
            }

            return response()->json([
                'message' => '更新成功！',
            ])->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [$e->getMessage()],
            ])->setStatusCode($e->getCode());
        }
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
            throw new Exception($data['message'], $mixed->getStatusCode());
        } elseif (! $mixed instanceof User) {
            throw new Exception('更新失败', 422);
        }
    }

    /**
     * 更新用户邮箱.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateUserEmail(Request $request, User $user)
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
