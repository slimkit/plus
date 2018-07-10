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

namespace Zhiyi\Plus\Packages\Wallet\TargetTypes;

use DB;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\Wallet;
use Zhiyi\Plus\Models\WalletCash as WalletCashModel;

class WidthdrawTarget extends Target
{
    const ORDER_TITLE = '提现';
    protected $wallet;

    protected $method = [
        Order::TYPE_INCOME => 'increment',
        Order::TYPE_EXPENSES => 'decrement',
    ];

    /**
     * Handle.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function handle($type, $account): bool
    {
        if (! $this->order->hasWait()) {
            return true;
        }

        $this->initWallet();

        $orderHandle = function () use ($type, $account) {
            $this->order->saveStateSuccess();
            $this->wallet->{$this->method[$this->order->getOrderModel()->type]}($this->order->getOrderModel()->amount);

            $this->createCash($type, $account);

            return true;
        };
        $orderHandle->bindTo($this);

        if (($result = DB::transaction($orderHandle)) === true) {
            $this->sendNotification();
        }

        return $result;
    }

    /**
     * 完成后的通知操作.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    protected function sendNotification()
    {
        // TODO
    }

    /**
     * 初始化钱包.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    protected function initWallet()
    {
        $this->wallet = new Wallet($this->order->getOrderModel()->owner_id);
    }

    /**
     * 创建提现申请.
     *
     * @param $type
     * @param $account
     * @return void
     * @author BS <414606094@qq.com>
     */
    protected function createCash($type, $account)
    {
        $cashModel = new WalletCashModel();
        $cashModel->user_id = $this->order->getOrderModel()->owner_id;
        $cashModel->value = $this->order->getOrderModel()->amount;
        $cashModel->type = $type;
        $cashModel->account = $account;
        $cashModel->status = 0;

        $cashModel->save();
    }
}
