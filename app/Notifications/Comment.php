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
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User as UserModel;
use function Zhiyi\Plus\setting;

class Comment extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;
    protected $sender;

    /**
     * Create a new notification instance.
     *
     * @param CommentModel $comment
     * @param UserModel $sender
     */
    public function __construct(CommentModel $comment, UserModel $sender)
    {
        $this->comment = $comment;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->id === $this->sender->id) {
            return [];
        }

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
     * @return JPushMessage
     */
    public function toJpush($notifiable): JPushMessage
    {
        $action = $notifiable->id === $this->comment->reply_user ? '回复' : '评论';
        $alert = sprintf('%s%s了你：%s', $this->sender->name, $action, $this->comment->body);
        $extras = [
            'tag' => 'notification:comments',
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
        return [
            'contents' => $this->comment->body,
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ],
            'commentable' => [
                'type' => $this->comment->commentable_type,
                'id' => $this->comment->commentable_id,
            ],
            'hasReply' => $notifiable->id === $this->comment->reply_user ? true : false,
        ];
    }
}
