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

namespace Zhiyi\Plus\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Zhiyi\Plus\Notifications\Channels\JPushChannel;
use function Zhiyi\Plus\setting;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification message.
     *
     * @var \Zhiyi\Plus\Notifications\Messages\UserNotificationMessage
     */
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Messages\UserNotificationMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return $this->getJPushSetting('open') ? ['database', JPushChannel::class] : ['database'];
    }

    /**
     * @param  string|null  $name
     * @return mixed
     */
    protected function getJPushSetting(string $name = null)
    {
        $setting = setting('user', 'vendor:jpush', []) + config('jpush', []);

        return $name === null ? $setting : $setting[$name] ?? null;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->message->toArray();
    }
}
