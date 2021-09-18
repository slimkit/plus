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

// use Illuminate\Database\Eloquent\Builder;

/**
 * Zhiyi\Plus\Models\WalletCash.
 *
 * @property int $id 提现记录ID
 * @property int $user_id 提现用户
 * @property int $value 需要提现的金额
 * @property string $type 提现方式
 * @property string $account 提现账户
 * @property int|null $status 状态：0 - 待审批，1 - 已审批，2 - 被拒绝
 * @property string|null $remark 备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Zhiyi\Plus\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletCash onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletCash whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|WalletCash withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletCash withoutTrashed()
 * @mixin \Eloquent
 */
class WalletCash extends Model
{
    use SoftDeletes;

    /**
     * User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
