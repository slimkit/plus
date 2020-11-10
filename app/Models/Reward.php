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
 * Zhiyi\Plus\Models\Reward.
 *
 * @property int $id
 * @property int $user_id 操作用户
 * @property int $target_user 目标用户
 * @property int $amount 打赏金额
 * @property string $rewardable_type
 * @property int $rewardable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $rewardable
 * @property-read \Zhiyi\Plus\Models\User|null $target
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRewardableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRewardableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereTargetUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUserId($value)
 * @mixin \Eloquent
 */
class Reward extends Model
{
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Has rewardable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rewardable()
    {
        return $this->morphTo();
    }

    /**
     * Has user for the rewardable.
     *
     * @author bs<414606094@qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Has target for the rewardable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function target()
    {
        return $this->hasOne(User::class, 'id', 'target_user');
    }
}
