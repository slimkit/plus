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

use Illuminate\Database\Eloquent\Model;

/**
 * Zhiyi\Plus\Models\UserCount.
 *
 * @property int $id 自增主键
 * @property int $user_id 所有者 ID
 * @property string $type 统计类型
 * @property int|null $total 统计总数
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCount whereUserId($value)
 * @mixin \Eloquent
 */
class UserCount extends Model
{
    protected $guarded = ['total'];
}
