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

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Notifications\UserNotification;
use Zhiyi\Plus\Notifications\Messages\UserNotificationMessage;

trait UserHasNotifiable
{
    public function routeNotificationForNexmo()
    {
        return $this->routeNotificationForSms();
    }

    public function routeNotificationForSms()
    {
        return $this->phone;
    }

    public function makeNotifyMessage(string $channel, string $content, array $extra = [])
    {
        return new UserNotificationMessage($channel, $content, $extra);
    }

    public function sendNotifyMessage(string $channel, string $content, array $extra = [])
    {
        return $this->notify(new UserNotification(
            $this->makeNotifyMessage($channel, $content, $extra)
        ));
    }
}
