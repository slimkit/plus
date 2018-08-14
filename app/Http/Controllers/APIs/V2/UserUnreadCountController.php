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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;
use Zhiyi\Plus\Models\AtMessage as AtMessageModel;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;
use Zhiyi\Plus\Models\Conversation as ConversationModel;

class UserUnreadCountController extends Controller
{
    use DateTimeToIso8601ZuluString;

    /**
     * 获取用户未读消息.
     *
     * @param Request $request
     * @param CommentModel $commentModel
     * @param LikeModel $likeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, CommentModel $commentModel, LikeModel $likeModel, PinnedsNotificationEventer $eventer)
    {
        $user = $request->user();
        $counts = $user->unreadCount ?? new \stdClass();

        // 查询最近几条评论记录
        $comments = $commentModel->select('user_id', DB::raw('max(id) as id, max(created_at) as time'))
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
        $likes = $likeModel->select('user_id', DB::raw('max(id) as id, max(created_at) as time'))
            ->where('target_user', $user->id)
            ->where('user_id', '!=', $user->id)
            ->limit(5)
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('id', 'desc')
            ->get();

        // 未处理审核统计
        $pinneds = $eventer->dispatch()->mapWithKeys(function ($pinnedModels) use ($user) {
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

        // $lastSystem = ConversationModel::where('type', 'system')
        //     ->where('to_user_id', $user->id)
        //     ->latest()
        //     ->first();
        $lastSystem = $user->notifications()
            ->latest()
            ->first();
        if ($lastSystem) {
            $lastSystem = $lastSystem->toArray();
        }

        $systemUnreadCount = $user->notifications()
            ->whereNull('read_at')
            ->count();

        // at 最近三个用户
        $atMeUsers = (new AtMessageModel)->query()
            ->with('user')
            ->where('user_id', $user->id)
            ->limit(3)
            ->orderBy('id', 'desc')
            ->get();
        $atLatestTimestamp = ($atMeUsersLatest = $atMeUsers->first()) ? $this->dateTimeToIso8601ZuluString($atMeUsersLatest->{AtMessageModel::CREATED_AT}) : null;

        $counts->system = $systemUnreadCount ?? 0;
        $result = array_filter([
            'counts' => $counts,
            'comments' => $comments,
            'likes' => $likes,
            'pinneds' => $pinneds,
            'system' => $lastSystem,
            'atme' => $atMeUsers->isEmpty() ? null : [
                'users' => $atMeUsers->map(function ($item) {
                    return $item->user->name ?? null;
                })->filter()->all(),
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
