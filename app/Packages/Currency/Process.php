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

namespace Zhiyi\Plus\Packages\Currency;

use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\CurrencyType as CurrencyTypeModel;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;

abstract class Process
{
    /**
     * 货币类型.
     *
     * @var [CurrencyTypeModel]
     */
    protected $currency_type;

    public function __construct()
    {
        $this->currency_type = CurrencyTypeModel::findOrFail(1);
    }

    /**
     * 检测用户模型.
     *
     * @param $user
     * @return UserModel
     * @author BS <414606094@qq.com>
     */
    public function checkUser($user): UserModel
    {
        if (is_numeric($user)) {
            return $this->checkWallet(
                UserModel::findOrFail((int) $user)
            );
        } elseif ($user instanceof UserModel) {
            return $this->checkWallet($user);
        }
    }

    /**
     * 检测用户货币模型，防止后续操作出现错误.
     *
     * @param UserModel $user
     * @return UserModel
     * @author BS <414606094@qq.com>
     */
    protected function checkWallet(UserModel $user): UserModel
    {
        $currency = $user->Currency;

        if (! $currency) {
            $user->Currency()->create(['type' => $this->currency_type->id, 'sum' => 0]);
        }

        return $user;
    }

    abstract protected function createOrder(int $owner_id, int $type, int $amount): CurrencyOrderModel;
}
