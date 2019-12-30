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
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;
use Zhiyi\Plus\Notifications\Follow as FollowNotification;

class CurrentUserController extends Controller
{
    /**
     * Get a single user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $user->load('wallet', 'newWallet', 'currency');

        $user->makeVisible(['phone', 'email']);
        $friends_count = $user->mutual()->get()->count();
        $user->friends_count = $friends_count;
        $user->initial_password = (bool) $user->password;

        return $response->json($user, 200);
    }

    /**
     * Update the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $rules = [
            'name' => ['nullable', 'string', 'username', 'display_length:2,12'],
            'bio' => ['nullable', 'string'],
            'sex' => ['nullable', 'numeric', 'in:0,1,2'],
            'location' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string', 'regex:/public:(.+)/is'],
            'bg' => ['nullable', 'string', 'regex:/public:(.+)/is'],
        ];
        $messages = [
            'name.string' => '用户名只能是字符串',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.display_length' => '用户名长度不合法',
            'bio.string' => '用户简介必须是字符串',
            'sex.numeric' => '发送的性别数据异常',
            'sex.in' => '发送的性别数据非法',
            'location.string' => '地区数据异常',
            'avatar.regex' => '头像错误',
            'bg.regex' => '背景图片错误',
        ];
        $this->validate($request, $rules, $messages);

        $target = ($name = $request->input('name'))
            ? $user->newQuery()->where('name', $name)->where('id', '!=', $user->id)->first()
            : null;
        if ($target) {
            return $response->json(['name' => ['用户名已被使用']], 422);
        }
        $fields = ['name', 'bio', 'sex', 'location', 'avatar', 'bg'];
        foreach ($fields as $field) {
            if ($request->request->has($field)) {
                $user->{$field} = $request->input($field);
            }
        }

        return $user->save()
            ? $response->make('', 204)
            : $response->json(['message' => ['更新失败']], 500);
    }

    /**
     * Update phone or email of the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\VerificationCode $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updatePhoneOrMail(Request $request, ResponseFactoryContract $response, VerificationCodeModel $model)
    {
        $user = $request->user();
        $email = $request->input('email');
        $phone = $request->input('phone');
        $code = $request->input('verifiable_code');

        if (($email && $phone) || (! $email && ! $phone)) {
            return $response->json(['message' => ['非法操作']], 422);
        }

        $this->validate($request, [
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'cn_phone'],
        ], [
            'email.email' => '请输入正确的 E-Mail',
            'phone.cn_phone' => '请输入符合大陆地区的手机号码',
        ]);

        $field = $email ? 'email' : 'phone';
        $target = $user->newQuery()
            ->where($field, $$field)
            ->where('id', '!=', $user->id)
            ->first();

        if ($target) {
            return $response->json([$field => ['已经被使用']], 422);
        }

        $code = $model->where('account', $$field)
            ->where('code', $code)
            ->first();

        if (! $code) {
            return $response->json(['message' => ['验证码错误或者已失效']], 422);
        }

        $user->$field = $$field;
        $code->delete();

        return $user->save()
            ? $response->make('', 204)
            : $response->json(['message' => ['操作失败']], 500);
    }

    /**
     * Show user followers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $followers = $user->followers()
            ->with('extra')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $user->getConnection()->transaction(function () use ($followers, $user, $response) {
            return $response->json($followers->map(function (UserModel $item) use ($user) {
                $item->following = true;
                $item->follower = $item->hasFollower($user);
                $item->blacked = $user->blacked($item);

                return $item;
            }))->setStatusCode(200);
        });
    }

    /**
     * Show user followings.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $followings = $user->followings()
            ->with('extra')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $user->getConnection()->transaction(function () use ($followings, $user, $response) {
            return $response->json($followings->map(function (UserModel $item) use ($user) {
                $item->following = $item->hasFollwing($user);
                $item->follower = true;
                $item->blacked = $user->blacked($item);

                return $item;
            }))->setStatusCode(200);
        });
    }

    /**
     * Attach a following user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $target
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function attachFollowingUser(Request $request, UserModel $target)
    {
        $user = $request->user();

        if ($user->id === $target->id) {
            return response()->json(['message' => ['不可对自己进行操作']], 422);
        } elseif ($user->hasFollwing($target)) {
            return response()->json(['message' => ['非法的操作']], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $target) {
            $user->followings()->attach($target);
            $user->extra()->firstOrCreate([])->increment('followings_count', 1);
            $target->extra()->firstOrCreate([])->increment('followers_count', 1);
        });

        $target->notify(new FollowNotification($user));

        return response('', 204);
    }

    /**
     * detach a following user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User $target
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function detachFollowingUser(Request $request, UserModel $target)
    {
        $user = $request->user();
        $user->getConnection()->transaction(function () use ($user, $target) {
            $user->followings()->detach($target);
            $user->extra()->decrement('followings_count', 1);
            $target->extra()->decrement('followers_count', 1);
        });

        return response('', 204);
    }

    /**
     * 获取授权用户相互关注的用户列表.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function followMutual(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', false);
        $keyword = $request->query('keyword', null);

        $followings = $user->mutual()
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name', 'like', "%{$keyword}%");
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $user->getConnection()->transaction(function () use ($followings, $user, $response) {
            return $response->json($followings->map(function (UserModel $item) use ($user) {
                $item->following = true;
                $item->follower = true;
                $item->blacked = $user->blacked($item);

                return $item;
            }))->setStatusCode(200);
        });
    }
}
