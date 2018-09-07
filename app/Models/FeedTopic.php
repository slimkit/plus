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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class FeedTopic extends Model
{
    public const REVIEW_PASSED = 'passed';
    public const REVIEW_WAITING = 'waiting';
    public const REVIEW_FAILED = 'failed';

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

    public function feeds(): BelongsToMany
    {
        $table = (new FeedTopicLink)->getTable();

        return $this
            ->belongsToMany(FeedModel::class, $table, 'topic_id', 'feed_id')
            ->using(FeedTopicLink::class);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'creator_user_id');
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
