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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zhiyi\Plus\Models\BlackList;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Zhiyi\Plus\Models\FeedTopicLink as FeedTopicLinkModel;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Models\PaidNode;
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\User;

class Feed extends Model
{
    use SoftDeletes,
        HasFactory,
        Concerns\HasFeedCollect,
        Relations\FeedHasReward,
        Relations\FeedHasLike,
        Relations\FeedHasVideo;
    /**
     * The model table name.
     *
     * @var string
     */
    protected $table = 'feeds';
    protected $fillable
        = [
            'feed_content',
            'feed_from',
            'feed_latitude',
            'feed_longtitude',
            'feed_client_id',
            'feed_goehash',
            'feed_mark',
            'user_id',
        ];
    protected $hidden = [
        'feed_client_id',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('topics', function (Builder $query) {
            $query->with('topics');
        });

        static::addGlobalScope('images', function (Builder $query) {
            $query->with([
                'images' => function (HasMany $query) {
                    $query->orderBy('id', 'asc');
                },
            ]);
        });

        static::addGlobalScope('video', function (Builder $builder) {
            $builder->with('video');
        });
        static::addGlobalScope('paidNode', function (Builder $builder) {
            $builder->with('paidNode');
        });
    }

    /**
     * Has feed pinned.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pinned()
    {
        return $this->hasOne(FeedPinned::class, 'target', 'id')
            ->where('channel', 'like', 'feed');
    }

    /**
     * blacklists of current user.
     *
     * @Author   Wayne
     * @DateTime 2018-04-13
     * @Email    qiaobin@zhiyicx.com
     * @return HasMany
     */
    public function blacks()
    {
        return $this->hasMany(BlackList::class, 'target_id', 'user_id');
    }

    /**
     * Get feed images.
     *
     * @return HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function images()
    {
        return $this->hasMany(FileWith::class, 'raw', 'id')
            ->where('channel', 'like', 'feed:image');
    }

    /**
     * 动态付费节点.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function paidNode()
    {
        return $this->hasOne(PaidNode::class, 'raw', 'id')
            ->where('channel', 'like', 'feeds');
    }

    /**
     * 单条动态属于一个用户.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)
            ->withTrashed();
    }

    /**
     * Has comments.
     *
     * @return MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has pinned comments.
     *
     * @return BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pinnedComments()
    {
        return $this->belongsToMany(Comment::class, 'feed_pinneds', 'raw',
            'target')
            ->where('channel', 'comment')
            ->where('expires_at', '>', new Carbon)
            ->orderBy('amount', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * comments are pinneding.
     */
    public function pinnedingComments()
    {
        return $this->hasMany(FeedPinned::class, 'raw', 'id')
            ->where('channel', 'like', 'comment')
            ->whereNull('expires_at');
    }

    /**
     * find the data from the user id.
     *
     * @param  Builder  $query
     * @param  int  $userId
     *
     * @return Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * find the data from the feed id.
     *
     * @param  Builder  $query  [description]
     * @param  int  $feedId  [description]
     *
     * @return Builder
     * @author bs<414606094@qq.com>
     */
    public function scopeByFeedId(Builder $query, int $feedId): Builder
    {
        return $query->where('id', $feedId);
    }

    /**
     * 筛选已审核动态
     *
     * @param  Builder  $query  [description]
     *
     * @return Builder
     * @author bs<414606094@qq.com>
     */
    public function scopeByAudit(Builder $query): Builder
    {
        return $query->where('audit_status', 1);
    }

    /**
     * 动态拥有多条收藏记录.
     *
     * @return HasMany
     */
    public function collection()
    {
        return $this->hasMany(FeedCollection::class, 'feed_id');
    }

    /**
     * Has reports.
     *
     * @return MorphMany
     * @author bs<414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * The feed topic belongs to many.
     *
     * @return BelongsToMany
     */
    public function topics(): BelongsToMany
    {
        $table = (new FeedTopicLinkModel)->getTable();

        return $this
            ->belongsToMany(FeedTopicModel::class, $table, 'feed_id',
                'topic_id')
            ->using(FeedTopicLinkModel::class)
            ->select('id', 'name');
    }

    public function makeHotValue($model = null): int
    {
        if (! $model instanceof static) {
            $model = $this;
        }

        return $model->feed_view_count + $model->feed_comment_count * 10
            + $model->like_count * 5;
    }
}
