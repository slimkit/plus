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
 * Zhiyi\Plus\Models\UserRecommended
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecommended whereUserId($value)
 * @mixin \Eloquent
 */
class UserRecommended extends Model
{
    protected $table = 'users_recommended';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
