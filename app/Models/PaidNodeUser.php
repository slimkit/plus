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
 * Zhiyi\Plus\Models\PaidNodeUser.
 *
 * @property int $node_id 付费发布ID
 * @property int $user_id 用户ID
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNodeUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNodeUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNodeUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNodeUser whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNodeUser whereUserId($value)
 * @mixin \Eloquent
 */
class PaidNodeUser extends Model
{
    //
}
