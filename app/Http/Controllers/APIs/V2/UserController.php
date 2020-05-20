<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use RuntimeException;
use Throwable;
use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Http\Requests\API2\StoreUserPost;
use Zhiyi\Plus\Models\Taggable;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\VerificationCode;
use function Zhiyi\Plus\setting;
use function Zhiyi\Plus\username;

class UserController extends Controller
{
    /**
     * Get all users.
     *
     * @param  Request  $request
     * @param  ResponseFactoryContract  $response
     * @param  User  $model
     *
     * @return mixed
     * @throws Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(
        Request $request,
        ResponseFactoryContract $response,
        User $model
    ) {
        $user = $request->user('api');
        $ids = array_filter(explode(',', $request->query('id', '')));
        $limit = max(min($request->query('limit', 15), 50), 1);
        $order = in_array($order = $request->query('order', 'desc'),
            ['asc', 'desc']) ? $order : 'desc';
        $since = $request->query('since', false);
        $name = $request->query('name', false);
        $fetchBy = $request->query('fetch_by', 'id');
        $tags = $request->query('tags', []);

        $users = $model->newQuery()
            ->when($since, function ($query) use ($since, $order) {
                return $query->where('id', $order === 'asc' ? '>' : '<',
                    $since);
            })
            ->when($name && $fetchBy !== 'username',
                function ($query) use ($name) {
                    return $query->where('name', 'like',
                        sprintf('%%%s%%', $name));
                })
            ->when(! empty($ids) && $fetchBy === 'id',
                function ($query) use ($ids) {
                    return $query->whereIn('id', $ids)->withTrashed();
                })
            ->when($name && $fetchBy === 'username',
                function ($query) use ($name) {
                    return $query->whereIn('name',
                        array_filter(explode(',', $name)));
                })
            ->when(is_array($tags) && ! empty($tags),
                function ($query) use ($tags) {
                    return $query->whereHas('tags',
                        function ($query) use ($tags) {
                            $taggableTable = (new Taggable)->getTable();

                            return $query->whereIn($taggableTable.'.tag_id',
                                $tags);
                        });
                })
            ->limit($limit)
            ->orderby('id', $order)
            ->get();
        $users->load('extra');
        $users->makeHidden([
            'updated_at', 'last_login_ip', 'certification', 'register_ip', 'created_at', 'email_verified_at',
            'phone_verified_at',
        ]);

        return $response->json($model->getConnection()->transaction(function () use ($users, $user) {
            return $users->map(function (User $item) use ($user) {
                $item->following = $item->hasFollwing($user->id ?? 0);
                $item->follower = $item->hasFollower($user->id ?? 0);
                $item->blacked = $user ? $user->blacked($item) : false;

                return $item;
            });
        }))->setStatusCode(200);
    }

    /**
     *  Get user.
     *
     * @param  Request  $request
     * @param  string  $user
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, string $user)
    {
        $field = username($user);
        $user = User::query()
            ->withTrashed()
            ->with('extra')
            ->where($field, $user)
            ->first();

        $user->makeVisible($field);
        $user->makeHidden([
            'updated_at', 'last_login_ip', 'certification', 'register_ip', 'created_at', 'email_verified_at',
            'phone_verified_at',
        ]);

        // 我关注的处理
        $this->hasFollowing($request, $user);
        // 处理关注我的
        $this->hasFollower($request, $user);
        // 处理黑名单
        $this->hasBlacked($request, $user);

        return response()->json($user, 200);
    }

    /**
     * 创建用户.
     *
     * @param  StoreUserPost  $request
     * @param  ResponseFactoryContract  $response
     * @param  JWTAuth  $auth
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(
        StoreUserPost $request,
        ResponseFactoryContract $response,
        JWTAuth $auth
    ) {
        $phone = $request->input('phone');
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $channel = $request->input('verifiable_type');
        $code = $request->input('verifiable_code');
        $role = setting('user', 'register-role');

        if (! $role) {
            throw new RuntimeException('Failed to get the defined user group.');
        }

        $verify = VerificationCode::where('account',
            $channel == 'mail' ? $email : $phone)
            ->where('channel', $channel)
            ->where('code', $code)
            ->orderby('id', 'desc')
            ->first();

        if (! $verify) {
            return $response->json(['message' => '验证码错误或者已失效'], 422);
        }

        $user = new User();
        $user->phone = $phone;
        $user->email = $email;
        $user->name = $name;
        $user->register_ip = $request->ip();

        if ($password !== null) {
            $user->createPassword($password);
        }

        $verify->delete();
        if (! $user->save()) {
            return $response->json(['message' => '注册失败'], 500);
        }

        $user->roles()->sync($role);

        return $response->json([
            'token'       => $auth->fromUser($user),
            'ttl'         => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ])->setStatusCode(201);
    }

    /**
     * Handle the state of my follow status.
     *
     * @param  Request  $request
     * @param  User &$user
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollowing(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('following',
            $currentUser ? $currentUser->id : 0);
        $user['following'] = $user->hasFollwing($hasUser);
    }

    /**
     * Verify that I am followed.
     *
     * @param  Request  $request
     * @param  User &$user
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollower(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('follower',
            $currentUser ? $currentUser->id : 0);
        $user['follower'] = $user->hasFollower($hasUser);
    }

    protected function hasBlacked(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $user['blacked'] = $currentUser ? $currentUser->blacked($user) : false;
    }
}
