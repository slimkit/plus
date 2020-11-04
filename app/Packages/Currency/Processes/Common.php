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

namespace Zhiyi\Plus\Packages\Currency\Processes;

use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Packages\Currency\Process;

class Common extends Process
{
    /**
     * 创建默认积分流水订单.
     *
     * @param  int  $owner_id
     * @param  int  $amount
     * @param  int  $type
     * @param  string  $title
     * @param  string  $body
     *
     * @return CurrencyOrderModel
     * @throws \Exception
     * @author BS <414606094@qq.com>
     */
    public function createOrder(
        int $owner_id,
        $amount,
        int $type,
        string $title,
        string $body
    ): CurrencyOrderModel {
        $user = $this->checkUser($owner_id);

        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = $type;
        $order->currency = $this->currency_type->get('id');
        $order->target_type = Order::TARGET_TYPE_COMMON;
        $order->target_id = 0;
        $order->amount = intval($amount);

        return $order;
    }
}
