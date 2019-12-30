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

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Notifications\At;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class UserUnreadCountController extends Controller
{
    use DateTimeToIso8601ZuluString;

    /**
     * 获取用户未读消息.
     *
     * @param  Request  $request
     * @param  CommentModel  $commentModel
     * @param  LikeModel  $likeModel
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(
        Request $request,
        CommentModel $commentModel,
        LikeModel $likeModel,
        PinnedsNotificationEventer $eventer
    ) {
        $user = $request->user();
        $counts = $user->unreadCount ?? new \stdClass();

        // 查询最近几条评论记录
        $comments = $commentModel->select('user_id',
            DB::raw('max(id) as id, max(created_at) as time'))
            ->where(function ($query) use ($user) {
                return $query->where('target_user', $user->id)
                    ->orWhere('reply_user', $user->id);
            })
            ->where('user_id', '!=', $user->id)
            ->limit(5)
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('id', 'desc')
            ->get();

        // 查询最近几条点赞记录
        $likes = $likeModel->select('user_id',
            DB::raw('max(id) as id, max(created_at) as time'))
            ->where('target_user', $user->id)
            ->where('user_id', '!=', $user->id)
            ->limit(5)
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('id', 'desc')
            ->get();

        // 未处理审核统计
        $pinneds = $eventer->dispatch()->mapWithKeys(function ($pinnedModels
        ) use ($user) {
            $model = new $pinnedModels['namespace']();

            $pinned = $model
                ->select(DB::raw('max(created_at) as time, count(*) as count'))
                ->where($pinnedModels['owner_prefix'], $user->id)
                ->where($pinnedModels['wherecolumn'])
                ->first();
            if (! $pinned || (! $pinned->time && ! $pinned->count)) {
                return [];
            }

            return [
                $pinnedModels['name'] => $pinned,
            ];
        });

        $lastSystem = $user->notifications()
            ->latest()
            ->first();
        if ($lastSystem) {
            $lastSystem = $lastSystem->toArray();
        }

        $systemUnreadCount = $user->notifications()
            ->whereNull('read_at')
            ->count();
        $atMeUsers = $user->notifications()->whereType(At::class)
            ->limit(3)
            ->get();

        // return $atMeUsers;
        $atLatestTimestamp = ($atMeUsersLatest = $atMeUsers->first())
            ? $this->dateTimeToIso8601ZuluString($atMeUsersLatest->created_at)
            : null;

        $counts->system = $systemUnreadCount ?? 0;
        $result = array_filter([
            'counts'   => $counts,
            'comments' => $comments,
            'likes'    => $likes,
            'pinneds'  => $pinneds,
            'system'   => $lastSystem,
            'atme'     => $atMeUsers->isEmpty()
                ? null
                : [
                    'users'     => $atMeUsers->map(function ($item) {
                        return $item->data['sender']['name'] ?? null;
                    })->filter()->unique()->values()->all(),
                    'latest_at' => $atLatestTimestamp,
                ],
        ], function ($item) {
            if (is_null($item)) {
                return false;
            } elseif ($item instanceof Collection) {
                return $item->isNotEmpty();
            }

            return true;
        });

        if (empty($result)) {
            $result = new \stdClass();
        }

        return response()->json($result, 200);
    }
}
