<?php

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Models\PayPublish;

trait HasPayNode
{
    public function payNodes()
    {
        return $this->belongsToMany(PayPublish::class, 'paid_node_users', 'user_id', 'node_id');
    }
}
