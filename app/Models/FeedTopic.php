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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeedTopic extends Model
{
    /**
     * The model table name.
     */
    protected $table = 'feed_topics';

    /**
     * Topic belongs to many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        $table = (new FeedTopicUserLink)->getTable();

        return $this
            ->belongsToMany(User::class, $table, 'topic_id', 'user_id')
            ->withPivot('index', Model::CREATED_AT)
            ->using(FeedTopicUserLink::class);
    }

    /**
     * The topic reports morph to many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
