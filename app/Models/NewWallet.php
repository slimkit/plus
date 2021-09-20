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
 * Zhiyi\Plus\Models\NewWallet.
 *
 * @property int $owner_id 钱包所属者
 * @property int $balance 钱包余额
 * @property int $total_income 总收入
 * @property int $total_expenses 总支出
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $owner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereTotalExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereTotalIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewWallet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NewWallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'total_expenses', 'total_income'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'owner_id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The wallet owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
