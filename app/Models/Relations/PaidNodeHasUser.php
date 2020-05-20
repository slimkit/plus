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

namespace Zhiyi\Plus\Models\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheName\CacheKeys;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Wallet;

trait PaidNodeHasUser
{
    // 发起支付节点人钱包.
    public function wallet()
    {
        return $this->hasManyThrough(Wallet::class, User::class, 'id',
            'user_id', 'user_id');
    }

    /**
     * Paid node users.
     *
     * @return BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'paid_node_users', 'node_id',
            'user_id');
    }

    /**
     * the author of paid.
     *
     * @return hasOne
     * @author bs<414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * To determine whether to pay for the node, to support the filter publisher.
     *
     * @param  int  $user  User ID
     * @param  bool  $filter
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function paid(int $user, bool $filter = true): bool
    {
        if ($filter === true && $this->user_id === $user) {
            return true;
        }

        $status = Cache::rememberForever(sprintf(CacheKeys::PAID, $this->id,
            $user), function () use ($user) {
                return $this->users()->newPivotStatementForId($user)->exists();
            });

        return $status;
    }
}
