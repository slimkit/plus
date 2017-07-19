<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class CurrentUserController extends Controller
{
    /**
     * Get a single user
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
     * Update the authenticated user
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
            'bio' => ['nullable', 'string'],
            'sex' => ['nullable', 'numeric', 'in:0,1,2'],
            'location' => ['nullable', 'string'],
        ];
        $messages = [
            'bio.string' => '用户简介必须是字符串',
            'sex.numeric' => '发送的性别数据异常',
            'sex.in' => '发送的性别数据非法',
            'location.string' => '地区数据异常',
        ];
        $this->validate($request, $rules, $messages);

        foreach ($request->only(['bio', 'sex', 'location']) as $key => $value) {
            if ($value) {
                $user->$key = $value;
            }
        }

        return $user->save()
            ? $response->make('', 204)
            : $response->json(['message' => ['更新失败']], 500);
    }

    /**
     * Update background image of the authenticated user
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
