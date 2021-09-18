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

use Illuminate\Database\Eloquent\Model;

/**
 * Zhiyi\Plus\Models\UserExtra.
 *
 * @property int $user_id 用户标识
 * @property int|null $likes_count 点赞统计
 * @property int|null $comments_count 评论统计
 * @property int|null $followers_count 粉丝统计
 * @property int|null $followings_count 关注数统计
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $feeds_count 用户分享统计
 * @property int|null $checkin_count 用户签到统计
 * @property int|null $last_checkin_count 用户连续签到统计
 * @property-read \Zhiyi\Plus\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereCheckinCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereFeedsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereFollowingsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereLastCheckinCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExtra whereUserId($value)
 * @mixin \Eloquent
 */
class UserExtra extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
