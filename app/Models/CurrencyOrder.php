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
 * Zhiyi\Plus\Models\CurrencyOrder.
 *
 * @property int $id
 * @property int $owner_id 记录所属者
 * @property string $title 订单标题
 * @property string|null $body 详情
 * @property int $type 1：入账、-1：支出
 * @property string $target_type 操作类型
 * @property string $target_id 目标id
 * @property int $currency 货币类型ID
 * @property int $amount 订单金额
 * @property int|null $state 订单状态，0: 等待，1：成功，-1: 失败
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurrencyOrder extends Model
{
    /**
     * the owner of order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
