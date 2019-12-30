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

namespace Zhiyi\Plus\API2\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Zhiyi\Plus\API2\Resources\Notification as NotificationResource;
use Zhiyi\Plus\Notifications\At as AtNotification;
use Zhiyi\Plus\Notifications\Comment as CommentNotification;
use Zhiyi\Plus\Notifications\Follow as FollowNotification;
use Zhiyi\Plus\Notifications\Like as LikeNotification;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class NotificationController extends Controller
{
    use DateTimeToIso8601ZuluString;

    /**
     * Get the notification controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get the request notification type.
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getQueryType(Request $request, bool $getTypes = false)
    {
        $type = $request->input('type');
        $types = [
            'at' => AtNotification::class,
            'comment' => CommentNotification::class,
            'like' => LikeNotification::class,
            'system' => SystemNotification::class,
        ];

        if ($getTypes) {
            return $types;
        } elseif (array_key_exists($type, $types)) {
            return $types[$type];
        }

        throw new UnprocessableEntityHttpException('type 不合法');
    }

    /**
     * Get the user notifications.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->whereType($this->getQueryType($request))
            ->paginate(15)
            ->appends([
                'type' => $request->query('type'),
            ]);

        return NotificationResource::collection($notifications);
    }

    /**
     * Set notifications make read at.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $type = $this->getQueryType($request);
        $notifications = $request->user()
            ->unreadNotifications()
            ->whereType($type)
            ->update([
                'read_at' => now(),
            ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Clear follow notifications.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function clearFollowNotifications(Request $request)
    {
        $request->user()->notifications()->whereType(FollowNotification::class)->delete();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the user notification statistics.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function statistics(Request $request)
    {
        $statistics = [];
        foreach ($this->getQueryType($request, true) as $alias => $notificationClassname) {
            $badge = $request->user()->unreadNotifications()->whereType($notificationClassname)->count();
            if ($notificationClassname === SystemNotification::class) {
                $first = $request->user()->notifications(SystemNotification::class)->first();
                $statistics[$alias] = [
                    'badge' => $badge,
                ];
                $statistics[$alias] = array_merge($statistics[$alias], (! $first) ? [] : [
                    'first' => new NotificationResource($first),
                ]);
                continue;
            }

            $lastCreatedAt = $this->dateTimeToIso8601ZuluString(
                $request->user()->notifications()->whereType($notificationClassname)->first()->created_at ?? null
            );
            $previewUserNames = $request->user()
                ->notifications()
                ->whereType($notificationClassname)
                ->limit(5)
                ->get()
                ->map(function ($notification) {
                    return $notification->data['sender']['name'];
                })
                ->filter()
                ->unique()
                ->values()
                ->all();
            $statistics[$alias] = [
                'badge' => $badge,
                'last_created_at' => $lastCreatedAt,
                'preview_users_names' => $previewUserNames,
            ];
        }

        $statistics['follow'] = [
            'badge' => $request->user()->unreadNotifications()->whereType(FollowNotification::class)->count(),
        ];

        return new JsonResponse($statistics);
    }
}
