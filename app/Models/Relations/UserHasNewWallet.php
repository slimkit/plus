<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Models\Relations;

use Zhiyi\Plus\Models\NewWallet;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserHasNewWallet
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function bootUserHasNewWallet()
    {
        // 用户创建后事件
        static::created(function ($user) {
            $wallet = new NewWallet();
            $wallet->owner_id = $user->id;
            $wallet->balance = 0;
            $wallet->total_income = 0;
            $wallet->total_expenses = 0;
            $wallet->save();

            if ($wallet === false) {
                return false;
            }
        });

        // 用户删除后事件
        // static::deleted(function ($user) {
        //     $user->wallet()->delete();
        // });
    }

    /**
     * User wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author BS <414606094@qq.com>
     */
    public function newWallet(): HasOne
    {
        return $this->hasOne(NewWallet::class, 'owner_id', 'id');
    }
}
