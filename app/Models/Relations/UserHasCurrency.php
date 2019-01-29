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
