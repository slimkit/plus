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
 * Zhiyi\Plus\Models\Sensitive
 *
 * @property int $id
 * @property string $word 敏感词
 * @property string $type 类型
 * @property string|null $replace 替换类型需要替换的文本
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereReplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sensitive whereWord($value)
 * @mixin \Eloquent
 */
class Sensitive extends Model
{
    //
}
