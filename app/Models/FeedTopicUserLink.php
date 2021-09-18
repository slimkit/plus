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

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Zhiyi\Plus\Models\FeedTopicUserLink.
 *
 * @property int $index The topic followers index
 * @property int $topic_id Be follow topic id
 * @property int $user_id Follow topic user id
 * @property int|null $feeds_count The user send to the topic feeds count
 * @property string|null $following_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereFeedsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereFollowingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedTopicUserLink whereUserId($value)
 * @mixin \Eloquent
 */
class FeedTopicUserLink extends Pivot
{
    /**
     * The model table name.
     */
    protected $table = 'feed_topic_user_links';

    /**
     * The pviot using primary key to index.
     */
    protected $primaryKey = 'index';
}
