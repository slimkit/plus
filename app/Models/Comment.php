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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Zhiyi\Plus\Models\Comment.
 *
 * @property int $id Comment ID.
 * @property int $user_id Send comment user.
 * @property int $target_user Target user.
 * @property int|null $reply_user Comments were answered.
 * @property string $body Comment body.
 * @property string $commentable_type
 * @property int $commentable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\BlackList[] $blacks
 * @property-read int|null $blacks_count
 * @property-read Model|\Eloquent $commentable
 * @property-read \Zhiyi\Plus\Models\User|null $reply
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \Zhiyi\Plus\Models\User $target
 * @property-read \Zhiyi\Plus\Models\User $user
 *
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereReplyUser($value)
 * @method static Builder|Comment whereTargetUser($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('user', function (Builder $query) {
            $query->with('user');
        });
        static::addGlobalScope('reply', function (Builder $query) {
            $query->with([
                'reply' => function (BelongsTo $belongsTo) {
                    $belongsTo->withoutGlobalScope('certification');
                },
            ]);
        });
    }

    /**
     * Has commentable.
     *
     * @return MorphTo
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    /**
     * Has a user.
     *
     * @return BelongsTo
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withTrashed();
    }

    /**
     * 被回复者.
     *
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     *
     * @return BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'target_user', 'id');
    }

    public function blacks()
    {
        return $this->hasMany(BlackList::class, 'target_id', 'user_id');
    }

    /**
     * 被回复者.
     *
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     *
     * @return BelongsTo
     */
    public function reply()
    {
        return $this->belongsTo(User::class, 'reply_user', 'id');
    }

    /**
     * 被举报记录.
     *
     * @return MorphMany
     *
     * @author BS <414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
