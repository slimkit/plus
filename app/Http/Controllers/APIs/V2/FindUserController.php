<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Taggable as TaggableModel;
use Zhiyi\Plus\Models\UserExtra as UserExtraModel;
use Zhiyi\Plus\Models\UserRecommended as UserRecommendedModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

/**
 * 找人.
 */
class FindUserController extends Controller
{
    /**
     * 热门用户, 根据粉丝数量倒序排列.
     */
    public function populars(Request $request, UserExtraModel $userExtra, ResponseContract $response)
    {
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);
        $u = $request->user();

        $users = $userExtra
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->limit($limit)
            ->select('user_id')
            ->with([
                'user',
            ])
            ->orderBy('followers_count', 'desc')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->user->following = $user->user->hasFollwing($u->id);
                $user->user->follower = $user->user->hasFollower($u->id);

                return $user->user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * 最新用户,按注册时间倒序.
     */
    public function latests(Request $request, UserModel $user, ResponseContract $response)
    {
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', null);
        $u = $request->user();

        $users = $user->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        })
            ->latest()
            ->limit($limit)
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->following = $user->hasFollwing($u->id);
                $user->follower = $user->hasFollower($u->id);

                return $user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * 推荐用户.
     */
    public function recommends(Request $request, UserRecommendedModel $userRecommended, ResponseContract $response)
    {
        $u = $request->user();
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);

        $users = $userRecommended->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        })
            ->with(['user'])
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->user->following = $user->user->hasFollwing($u->id);
                $user->user->follower = $user->user->hasFollower($u->id);

                return $user->user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * search users by name.
     */
    public function search(Request $request, UserModel $user, ResponseContract $response)
    {
        $u = $request->user();
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);
        $keyword = $request->input('keyword', null);

        if (! $keyword) {
            abort(422, '请输入关键字');
        }

        $users = $user->where('name', 'like', "%{$keyword}%")
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->following = $user->hasFollwing($u->id);
                $user->follower = $user->hasFollower($u->id);

                return $user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * 通过标签推荐用户.
     */
    public function findByTags(Request $request, TaggableModel $taggable, ResponseContract $response)
    {
        $u = $request->user();
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);

        $tags = $u->tags()->select('tag_id')->get();

        $tags = array_pluck($tags, 'tag_id');

        $users = $taggable->whereIn('tag_id', $tags)
            ->where('taggable_type', 'users')
            ->where('taggable_id', '<>', $u->id)
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->limit($limit)
            ->with(['user'])
            ->select('taggable_id')
            ->groupBy('taggable_id')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->user->following = $user->user->hasFollwing($u->id);
                $user->user->follower = $user->user->hasFollower($u->id);

                return $user->user;
            })
        )
        ->setStatusCode(200);
    }

    public function findByPhone(Request $request, UserModel $user, ResponseContract $response)
    {
        $u = $request->user();
        $phones = $request->input('phones', '');

        if (! $phones) {
            abort(422, '请传递手机号码');
        }

        $users = $user
            ->select('*')
            ->whereIn('phone', $phones)
            ->limit(100)
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->following = $user->hasFollwing($u->id);
                $user->follower = $user->hasFollower($u->id);
                $user->mobi = $user->phone;

                return $user;
            })
        )
        ->setStatusCode(200);
    }
}
