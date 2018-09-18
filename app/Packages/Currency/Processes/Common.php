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

namespace Zhiyi\Plus\Packages\Currency\Processes;

use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Packages\Currency\Process;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;

class Common extends Process
{
    /**
     * 创建默认积分流水订单.
     *
     * @param int $owner_id
     * @param int $amount
     * @param string $title
     * @param string $body
     * @return Zhiyi\Plus\Models\CurrencyOrder
     * @author BS <414606094@qq.com>
     */
    public function createOrder(int $owner_id, int $amount, int $type, string $title, string $body): CurrencyOrderModel
    {
        $user = $this->checkUser($owner_id);

        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = $type;
        $order->currency = $this->currency_type->id;
        $order->target_type = Order::TARGET_TYPE_COMMON;
        $order->target_id = 0;
        $order->amount = $amount;

        return $order;
    }
}
