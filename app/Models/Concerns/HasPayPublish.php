<?php

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Models\PayPublish;
use Zhiyi\Plus\Models\PayPublishUser;

trait HasPayPublish
{
    public function payPublishes()
    {
        return $this->belongsToMany(PayPublish::class, 'pay_publish_users', 'user_id', 'publish_id');
    }
}