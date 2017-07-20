<?php

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
