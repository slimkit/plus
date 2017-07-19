<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use RuntimeException;
use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\VerificationCode;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreUserPost;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserController extends Controller
{
    /**
     * Get all users.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, User $model)
    {
        $user = $request->user('api') ?: 0;
        $limit = max(min($request->query('limit', 20), 50), 1);
        $order = in_array($order = $request->query('order', 'desc'), ['asc', 'desc']) ? $order : 'desc';
        $since = $request->query('since', false);
        $name = $request->query('name', false);

        $users = $model->when($since, function ($query) use ($since, $order) {
            return $query->where('id', $order === 'asc' ? '>' : '<', $since);
        })->when($name, function ($query) use ($name) {
            return $query->where('name', 'like', sprintf('%%%s%%', $name));
        })->limit($limit)
          ->orderby('id', $order)
          ->get();

        return $response->json($model->getConnection()->transaction(function () use ($users, $user) {
            return $users->map(function (User $item) use ($user) {
                $item->following = $item->hasFollwing($user);
                $item->follower = $item->hasFollower($user);

                return $item;
            });
        }))->setStatusCode(200);
    }

    /**
     *  Get user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, User $user)
    {
        // 我关注的处理
        $this->hasFollowing($request, $user);
        // 处理关注我的
        $this->hasFollower($request, $user);

        return response()->json($user, 200);
    }

    /**
     * 创建用户.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUserPost $request, ResponseFactoryContract $response, JWTAuth $auth)
    {
        $phone = $request->input('phone');
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $channel = $request->input('verifiable_type');
        $code = $request->input('verifiable_code');

        $role = CommonConfig::byNamespace('user')
            ->byName('default_role')
            ->firstOr(function () {
                throw new RuntimeException('Failed to get the defined user group.');
            });

        $verify = VerificationCode::where('account', $channel == 'mail' ? $email : $phone)
            ->where('channel', $channel)
            ->where('code', $code)
            ->orderby('id', 'desc')
            ->first();

        if (! $verify) {
            return $response->json(['message' => ['验证码错误或者已实效']], 422);
        }

        $user = new User();
        $user->phone = $phone;
        $user->email = $email;
        $user->name = $name;
        $user->createPassword($password);

        $verify->delete();
        if (! $user->save()) {
            return $response->json(['message' => ['注册失败']], 500);
        }

        $user->attachRole($role->value);

        return $response->json([
            'token' => $auth->fromUser($user),
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ])->setStatusCode(201);
    }

    /**
     * 处理我关注的状态.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User &$user
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollowing(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('following', $currentUser ? $currentUser->id : 0);
        $user['following'] = $user->hasFollwing($hasUser);
    }

    /**
     * 验证是否关注了我.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User &$user
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollower(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('follower', $currentUser ? $currentUser->id : 0);
        $user['follower'] = $user->hasFollower($hasUser);
    }
}
