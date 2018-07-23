<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FeedTopicFollower extends Pivot
{
    /**
     * The model table name.
     */
    protected $table = 'feed_topic_followers';

    /**
     * The pviot using primary key to index.
     */
    protected $primaryKey = 'index';
}
