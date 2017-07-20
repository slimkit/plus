<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
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
        $limit = intval($request->query('limit', 20));
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
            return $response->json(['message' => ['通知不存在或者已被删除']])->setStatusCode(404);
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

        return $response->json(['message' => ['操作成功']])->setStatusCode(201);
    }
}
