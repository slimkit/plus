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
 * Zhiyi\Plus\Models\NativePayOrder.
 *
 * @property int $id
 * @property string $type 支付类型: alipay,wechatPay
 * @property string $out_trade_no 站内订单号
 * @property string|null $trade_no 第三方交易订单号
 * @property int $status 交易状态, 默认等待确认0, 1成功, 2失败
 * @property int $amount 交易金额: 分为单位
 * @property int $user_id 订单发起人id
 * @property string $subject 订单抬头
 * @property string $content 订单内容
 * @property string $product_code 订单交易方式
 * @property int $from 充值订单来自哪个客户端
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User $user
 * @property-read \Zhiyi\Plus\Models\WalletCharge|null $walletCharge
 *
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereOutTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NativePayOrder whereUserId($value)
 * @mixin \Eloquent
 */
class NativePayOrder extends Model
{
    protected $table = 'native_pay_orders';

    public function walletCharge()
    {
        return $this->hasOne(WalletCharge::class, 'charge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
