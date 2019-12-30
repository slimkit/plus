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

use DB;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Packages\Currency\Process;
use function Zhiyi\Plus\setting;

class Cash extends Process
{
    /**
     * 创建积分提取记录.
     *
     * @param int $owner_id
     * @param int $amount
     * @return Zhiyi\Plus\Models\CurrencyOrder
     * @author BS <414606094@qq.com>
     */
    public function createOrder(int $owner_id, int $amount): CurrencyOrderModel
    {
        $user = $this->checkUser($owner_id);
        $ratio = setting('currency', 'settings')['recharge-ratio'] ?? 1;

        // 积分除兑换比例取整，保证兑换的人民币为分单位的整数
        $amount = $amount - ($amount % $ratio);

        $title = '积分提取';
        $body = sprintf('提取积分：%s%s%s', $amount, $this->currency_type['unit'], $this->currency_type['name']);

        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = -1;
        $order->currency = $this->currency_type['id'];
        $order->target_type = Order::TARGET_TYPE_CASH;
        $order->target_id = 0;
        $order->amount = $amount;

        return DB::transaction(function () use ($order, $user) {
            $user->currency->decrement('sum', $order->amount);
            $order->save();

            return $order;
        });
    }
}
