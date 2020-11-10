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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

/**
 * Zhiyi\Plus\Models\FeedTopicLink
 *
 * @property int $index The topic link index
 * @property int $topic_id Topic ID
 * @property int $feed_id Feed ID
 * @property-read FeedModel|null $feed
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicLink whereTopicId($value)
 * @mixin \Eloquent
 */
class FeedTopicLink extends Pivot
{
    /**
     * The povot table name.
     */
    protected $table = 'feed_topic_links';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The pviot using primary key to index.
     */
    protected $primaryKey = 'index';

    /**
     * Load the link has feed relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feed(): HasOne
    {
        return $this->hasOne(FeedModel::class, 'id', 'feed_id');
    }
}
