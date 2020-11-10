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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Zhiyi\Plus\Models\WalletCharge.
 *
 * @property int $id
 * @property int|null $user_id 关联用户，可不存在，例如直接支付方式等。存在便于按照用户检索。
 * @property string $channel 支付频道，参考 Ping++，增加 user 选项，表示站内用户凭据
 * @property string|null $account 交易账户，减项为目标账户，增项为来源账户，当 type 为 user 时，此处是用户ID
 * @property string|null $charge_id 凭据id, 来自 Ping ++
 * @property int $action 类型：1 - 增加，0 - 减少
 * @property int $amount 总额
 * @property string|null $currency 货币类型
 * @property string $subject 订单标题
 * @property string $body 订单详情
 * @property string|null $transaction_no 平台记录ID
 * @property int|null $status 状态：0 - 等待, 1 - 成功, 2 - 失败
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletCharge onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereTransactionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCharge whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|WalletCharge withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletCharge withoutTrashed()
 * @mixin \Eloquent
 */
class WalletCharge extends Model
{
    use SoftDeletes;

    /**
     * User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
