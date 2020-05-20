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

namespace Zhiyi\Plus\Packages\Currency;

use Zhiyi\Plus\Models\CurrencyType as CurrencyTypeModel;
use Zhiyi\Plus\Models\User as UserModel;

class Process
{
    /**
     * 货币类型.
     *
     * @var [CurrencyTypeModel]
     */
    protected $currency_type;

    public function __construct()
    {
        $this->currency_type = CurrencyTypeModel::current();
    }

    /**
     * 检测用户模型.
     *
     * @param $user
     * @param  bool  $throw
     *
     * @return UserModel | bool
     * @throws \Exception
     * @author BS <414606094@qq.com>
     */
    public function checkUser($user, $throw = true)
    {
        if (is_numeric($user)) {
            $user = UserModel::find((int) $user);
        }

        if (! $user) {
            if ($throw) {
                throw new \Exception('找不到所属用户', 1);
            }

            return false;
        }

        return $this->checkCurrency($user);
    }

    /**
     * 检测用户货币模型，防止后续操作出现错误.
     *
     * @param  UserModel  $user
     *
     * @return UserModel
     * @author BS <414606094@qq.com>
     */
    protected function checkCurrency(UserModel $user): UserModel
    {
        if (! $user->currency) {
            $user->currency = $user->currency()
                ->create(['type' => $this->currency_type->get('id'), 'sum' => 0]);
        }

        return $user;
    }
}
