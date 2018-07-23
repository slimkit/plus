<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FeedTopicLink extends Pivot
{
    /**
     * The povot table name.
     */
    protected $table = 'feed_topic_links';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    protected $incrementing = false;
}
