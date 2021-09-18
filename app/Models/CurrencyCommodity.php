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
 * Zhiyi\Plus\Models\CurrencyCommodity.
 *
 * @property int $id
 * @property int $creator 创建者用户ID
 * @property int $currency 货币类型ID
 * @property int $amount 货币总价
 * @property string $title 商品标题
 * @property string|null $body 商品详情
 * @property int $purchase_count 购买统计
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity wherePurchaseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyCommodity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurrencyCommodity extends Model
{
    //
}
