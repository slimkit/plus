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
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Zhiyi\Plus\Models\WalletOrder.
 *
 * @property int $id
 * @property int $owner_id 记录所属者
 * @property string $target_type 目标类型
 * @property string $target_id 目标标识
 * @property string $title 订单标题
 * @property string|null $body 详情
 * @property int $type 1：入账、-1：支出
 * @property int $amount 订单金额
 * @property int|null $state 订单状态，0: 等待，1：成功，-1: 失败
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $owner
 * @property-read \Zhiyi\Plus\Models\NewWallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WalletOrder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_orders';

    /**
     * The order owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    /**
     * The order owner wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(NewWallet::class, 'owner_id', 'owner_id');
    }
}
