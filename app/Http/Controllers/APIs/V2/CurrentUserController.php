<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

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
        $user->load('wallet');

        return $response->json(array_merge($user->toArray(), [
            'phone' => $user->phone,
            'email' => $user->email,
        ]))->setStatusCode(200);

        return response()->json($user, 200);
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
        ];
        $messages = [
            'name.string' => '用户名只能是字符串',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.display_length' => '用户名长度不合法',
            'bio.string' => '用户简介必须是字符串',
            'sex.numeric' => '发送的性别数据异常',
            'sex.in' => '发送的性别数据非法',
            'location.string' => '地区数据异常',
        ];
        $this->validate($request, $rules, $messages);

        $target = ($name = $request->input('name'))
            ? $user->newQuery()->where('name', $name)->where('id', '!=', $user->id)->first()
            : null;
        if ($target) {
            return $response->json(['name' => ['用户名已被使用']], 422);
        }

        foreach ($request->only(['name', 'bio', 'sex', 'location']) as $key => $value) {
            if (isset($value)) {
                $user->$key = $value;
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
     * Update background image of the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function uploadBgImage(Request $request, ResponseFactoryContract $response)
    {
        $this->validate($request, ['image' => ['required', 'image']], [
            'image.required' => '请上传图片',
            'image.image' => '上传的文件必须是图像',
        ]);

        $image = $request->file('image');

        return $request->user()->storeAvatar($image, 'user-bg')
            ? $response->make('', 204)
            : $response->json(['message' => ['上传失败']], 500);
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
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followers = $user->followers()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $user->getConnection()->transaction(function () use ($followers, $user, $response) {
            return $response->json($followers->map(function (UserModel $item) use ($user) {
                $item->following = true;
                $item->follower = $item->hasFollower($user);

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
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followings = $user->followings()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $user->getConnection()->transaction(function () use ($followings, $user, $response) {
            return $response->json($followings->map(function (UserModel $item) use ($user) {
                $item->following = $item->hasFollwing($user);
                $item->follower = true;

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
    public function attachFollowingUser(Request $request, ResponseFactoryContract $response, UserModel $target)
    {
        $user = $request->user();

        if ($user->id === $target->id) {
            return $response->json(['message' => ['不可对自己进行操作']], 422);
        } elseif ($user->hasFollwing($target)) {
            return $response->json(['message' => ['非法的操作']], 422);
        }

        return $user->getConnection()->transaction(function () use ($user, $target, $response) {
            $user->followings()->attach($target);
            $user->extra()->firstOrCreate([])->increment('followings_count', 1);
            $target->extra()->firstOrCreate([])->increment('followers_count', 1);

            $message = sprintf('%s关注了你，去看看吧', $user->name);
            $target->sendNotifyMessage('user:follow', $message, [
                'user' => $user,
            ]);

            return $response->make(null, 204);
        });
    }

    /**
     * detach a following user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $target
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function detachFollowingUser(Request $request, ResponseFactoryContract $response, UserModel $target)
    {
        $user = $request->user();

        return $user->getConnection()->transaction(function () use ($user, $target, $response) {
            $user->followings()->detach($target);
            $user->extra()->decrement('followings_count', 1);
            $target->extra()->decrement('followers_count', 1);

            return $response->make('', 204);
        });
    }
}
