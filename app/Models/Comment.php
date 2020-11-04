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
     * @author BS <414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
