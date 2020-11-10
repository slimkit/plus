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
 * 注册时关注用户模型.
 *
 * @property int $id
 * @property int $user_id 被设置用户的id
 * @property string $type 类型[ {each: 相互关注}, {followed: 被关注}]
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Famous newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Famous newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Famous query()
 * @method static \Illuminate\Database\Eloquent\Builder|Famous whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Famous whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Famous whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Famous whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Famous whereUserId($value)
 * @mixin \Eloquent
 */
class Famous extends Model
{
    protected $table = 'famous';

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
