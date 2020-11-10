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
 * Zhiyi\Plus\Models\BlackList
 *
 * @property int $user_id main user
 * @property int $target_id blacked user id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlackList whereUserId($value)
 * @mixin \Eloquent
 */
class BlackList extends Model
{
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'target_id');
    }
}
