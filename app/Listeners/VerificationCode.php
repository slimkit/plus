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

namespace Zhiyi\Plus\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;

class VerificationCode implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        if ($this->validate($notifiable = $event->notifiable)) {
            $notifiable->state = 1;
            $notifiable->save();
        }
    }

    /**
     * Failed handle the event.
     *
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     * @param mixed $exception
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function failed(NotificationSent $event, $exception)
    {
        if ($this->validate($notifiable = $event->notifiable)) {
            $notifiable->state = 2;
            $notifiable->save();
        }

        throw $exception;
    }

    /**
     * Validate the event notifiable instance of verification code model.
     *
     * @param mixed $notifiable
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validate($notifiable): bool
    {
        return $notifiable instanceof VerificationCodeModel;
    }
}
