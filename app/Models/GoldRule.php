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
 * Zhiyi\Plus\Models\GoldRule.
 *
 * @property int $id
 * @property string $name 金币规则名称
 * @property string $alias 金币规则别名
 * @property string $desc
 * @property int $incremental 金币规则增量
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereIncremental($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoldRule extends Model
{
    public $table = 'gold_rules';

    public $fillable = ['name', 'alias', 'desc', 'incremental'];
}
