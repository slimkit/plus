<?php

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
 * Zhiyi\Plus\Models\UserFollow.
 *
 * @property int $id 关注ID
 * @property int $user_id 对象用户
 * @property int $target 目标用户
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollow whereUserId($value)
 * @mixin \Eloquent
 */
class UserFollow extends Pivot
{
    //
}
