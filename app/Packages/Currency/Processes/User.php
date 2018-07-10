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

use DB;
use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Models\User as UserModel;
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
    public function complete(int $owner_id, int $amount, int $target_id, array $extra): bool
    {
        $extra = $this->checkDefaultParam($amount, $extra);

        $user = $this->checkUser($owner_id);
        $target_user = $this->checkUser($target_id, false);

        return DB::transaction(function () use ($user, $target_id, $target_user, $amount, $extra) {
            $order = $this->createOrder($user, $amount, -1, $extra['order_title'], $extra['order_body'], $target_id);
            $order->state = 1;
            $order->save();
            $user->currency->decrement('sum', $amount);

            if ($target_user) {
                $target_order = $this->createOrder($target_user, $amount, 1, $extra['target_order_title'], $extra['target_order_body'], $user->id);
                $target_user->currency->increment('sum', $amount);
                $target_order->state = 1;
                $target_order->save();
            }

            return true;
        });
    }

    /**
     * 用户对用户预付款流程.
     *
     * @param int $owner_id
     * @param int $amount
     * @param int $target_id
     * @param string $title
     * @param string $body
     * @return bool
     * @author BS <414606094@qq.com>
     */
    public function prepayment(int $owner_id, int $amount, int $target_id, string $title, string $body): bool
    {
        $user = $this->checkUser($owner_id);

        return DB::transaction(function () use ($user, $target_id, $amount, $title, $body) {
            $order = $this->createOrder($user, $amount, -1, $title, $body, $target_id);
            $order->state = 1;
            $order->save();
            $user->currency->decrement('sum', $amount);

            return true;
        });
    }

    /**
     * 用户收款流程.
     *
     * @param int $owner_id
     * @param int $amount
     * @param int $target_id
     * @param string $title
     * @param string $body
     * @return bool
     * @author BS <414606094@qq.com>
     */
    public function receivables(int $owner_id, int $amount, int $target_id, string $title, string $body): bool
    {
        $user = $this->checkUser($owner_id);

        return DB::transaction(function () use ($user, $target_id, $amount, $title, $body) {
            $order = $this->createOrder($user, $amount, 1, $title, $body, $target_id);
            $order->state = 1;
            $order->save();
            $user->currency->increment('sum', $amount);

            return true;
        });
    }

    /**
     * 用户拒绝，对方回款流程.
     *
     * @param int $owner_id
     * @param int $amount
     * @param int $target_id
     * @param string $title
     * @param string $body
     * @return bool
     * @author BS <414606094@qq.com>
     */
    public function reject(int $owner_id, int $amount, int $target_id, string $title, string $body): bool
    {
        // 对方用户
        $user = $this->checkUser($target_id);

        return DB::transaction(function () use ($user, $owner_id, $amount, $title, $body) {
            $order = $this->createOrder($user, $amount, 1, $title, $body, $owner_id);
            $order->save();
            $user->currency->increment('sum', $amount);

            return true;
        });
    }

    /**
     * 创建订单方法.
     *
     * @param int $owner_id
     * @param int $amount
     * @param int $type
     * @param string $title
     * @param string $body
     * @param int|int $target_id
     * @return Zhiyi\Plus\Models\CurrencyOrder
     * @author BS <414606094@qq.com>
     */
    public function createOrder(UserModel $user, int $amount, int $type, string $title = '', string $body = '', int $target_id = 0): CurrencyOrderModel
    {
        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = $type;
        $order->target_id = $target_id;
        $order->currency = $this->currency_type->id;
        $order->target_type = Order::TARGET_TYPE_USER;
        $order->amount = $amount;

        return $order;
    }

    /**
     * 检测保存订单需要的参数.
     *
     * @param array $extra
     * @return array
     * @author BS <414606094@qq.com>
     */
    private function checkDefaultParam(int $amount, array $extra): array
    {
        $defaultExtra = [
            'order_title' => '支出积分',
            'order_body' => sprintf('支出%s积分', $amount),
            'target_order_title' => '收入积分',
            'target_order_body' => sprintf('收入%s积分', $amount),
        ];

        return array_merge($defaultExtra, $extra);
    }
}
