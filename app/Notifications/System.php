<?php

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
use Medz\Laravel\Notifications\JPush\Message as JPushMessage;
use function Zhiyi\Plus\setting;

class System extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $resource;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, array $resource)
    {
        $this->message = $message;
        $this->resource = $resource;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->getJPushSetting('open') ? ['database', 'jpush'] : ['database'];
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
     * Get the JPush representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Medz\Laravel\Notifications\JPush\Message
     */
    public function toJpush($notifiable): JPushMessage
    {
        $alert = $this->message;
        $extras = [
            'tag' => 'notification:system',
        ];

        $payload = new JPushMessage;
        $payload->setMessage($alert, [
            'content_type' => $extras['tag'],
            'extras' => $extras,
        ]);
        $payload->setNotification(JPushMessage::IOS, $alert, [
            'content-available' => false,
            'thread-id' => $extras['tag'],
            'extras' => $extras,
        ]);
        $payload->setNotification(JPushMessage::ANDROID, $alert, [
            'extras' => $extras,
        ]);
        $payload->setOptions([
            'apns_production' => boolval(config('services.jpush.apns_production')),
        ]);

        return $payload;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->resource;
    }
}
