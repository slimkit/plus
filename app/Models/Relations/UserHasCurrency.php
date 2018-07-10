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

use Zhiyi\Plus\Models\Currency;

trait UserHasCurrency
{
    public static function bootUserHasCurrency()
    {
        // 用户创建后事件
        static::created(function ($user) {
            $currency = Currency::firstOrCreate(
                ['owner_id' => $user->id],
                ['type' => 1, 'sum' => 0]
            );

            if ($currency === false) {
                return false;
            }
        });
    }

    /**
     * user has currencies.
     *
     * @author BS <414606094@qq.com>
     */
    public function currency()
    {
        return $this->hasOne(Currency::class, 'owner_id', 'id');
    }
}
