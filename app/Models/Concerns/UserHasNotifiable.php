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

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Notifications\Messages\UserNotificationMessage;
use Zhiyi\Plus\Notifications\UserNotification;

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
