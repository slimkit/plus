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

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\UserCount;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;

// use Illuminate\Notifications\DatabaseNotification as NotificationModel;

class UserNotificationController extends Controller
{
    /**
     * Get user notifications.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ContractResponse $response)
    {
        $user = $request->user();
        $typeMap = [
            'all' => 'notifications',
            'read' => 'readNotifications',
            'unread' => 'unreadNotifications',
        ];
        $limit = intval($request->query('limit', 15));
        $offset = intval($request->query('offset', 0));
        $method = in_array($type = $request->query('type'), array_keys($typeMap)) ? $typeMap[$type] : $typeMap['all'];
        $notification = array_filter(is_string($notification = $request->query('notification')) ? explode(',', $notification) : [$notification]);
        $unreadNotificationLimit = $user->unreadNotifications()->count();

        if (strtolower($request->method()) === 'head') {
            return $response->make(null, 200, ['unread-notification-limit' => $unreadNotificationLimit]);
        }

        $notifications = $user->$method()
            ->when(! empty($notification), function ($query) use ($notification) {
                return $query->whereIn('id', $notification);
            })
            ->limit($limit)
            ->offset($offset)
            ->get(['id', 'read_at', 'data', 'created_at']);

        return $response->json($notifications)
            ->header('unread-notification-limit', $unreadNotificationLimit)
            ->setStatusCode(200);
    }

    /**
     * Get notification message.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param string $notification
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ContractResponse $response, string $notification)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->first(['id', 'read_at', 'data', 'created_at']);

        if (! $notification) {
            return $response->json(['message' => '通知不存在或者已被删除'])->setStatusCode(404);
        }

        $notification->markAsRead();

        return $response->json($notification)->setStatusCode(200);
    }

    /**
     * Mark notification status to read.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param string $notification
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function markAsRead(Request $request, ContractResponse $response, string $notification = '')
    {
        $notifications = array_filter(array_merge(
            is_string($notifications = $request->query('notification')) ? explode(',', $notifications) : [$notifications],
            [$notification]
        ));

        $request->user()->unreadNotifications()->whereIn('id', $notifications)->get()->markAsRead();
        // 清空用户未读系统通知未读数
        $userCount = UserCount::firstOrNew([
            'user_id' => $request->user()->id,
            'type' => 'user-system',
        ]);

        $userCount->total -= 1;
        $userCount->save();

        return $response->json(['message' => '操作成功'])->setStatusCode(201);
    }

    /**
     * 标记所有未读消息为已读.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->where('read_at', null)->get()->markAsRead();
        // 清空用户未读系统通知未读数
        $userCount = UserCount::firstOrNew([
            'user_id' => $request->user()->id,
            'type' => 'user-system',
        ]);

        $userCount->total = 0;
        $userCount->save();

        return response()->json(['message' => '操作成功'])->setStatusCode(201);
    }
}
