<?php

namespace Zhiyi\Plus\Models\Concerns;

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
}
