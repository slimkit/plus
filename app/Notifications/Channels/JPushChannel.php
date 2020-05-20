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

namespace Zhiyi\Plus\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Zhiyi\Plus\Services\Push;

class JPushChannel
{
    protected $pushClient;

    public function __construct(Push $pushClient)
    {
        $this->pushClient = $pushClient;
    }

    /**
     * 发送给定通知.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toArray($notifiable);

        $audience = [(string) $notifiable->id];
        $extra = ['channel' => $message['channel']];
        $alert = $message['content'];

        // 将通知发送给 $notifiable 实例
        $this->pushClient->push($alert, $audience, $extra);
    }
}
