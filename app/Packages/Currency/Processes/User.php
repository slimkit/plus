<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Packages\Currency\Processes;

use DB;
use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Packages\Currency\Process;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;

class User extends Process
{
    /**
     * 自动完成订单方法.
     *
     * @param int $owner_id
     * @param int $amount
     * @param string $title
     * @param string $body
     * @param int|int $target_id
     * @return bool
     * @author BS <414606094@qq.com>
     */
    public function complete(int $owner_id, int $amount, string $title = '', string $body = '', int $target_id = 0): bool
    {
        $user = $this->checkUser($owner_id);
        $target_user = $this->checkUser($target_id);

        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = 1;
        $order->currency = $this->currency_type->id;
        $order->target_type = Order::TARGET_TYPE_USER;
        $order->target_id = $target_id;
        $order->amount = $amount;
        $order->state = 1;

        return DB::transaction(function () use ($order, $user, $target_user) {
            $order->save();
            $user->currency->decrement('sum', $amount);
            $target_user->currency->increment('sum', $amount);

            return true;
        });
    }
}
