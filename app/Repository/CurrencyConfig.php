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

namespace Zhiyi\Plus\Repository;

use function Zhiyi\Plus\setting;

class CurrencyConfig
{
    /**
     * Get the Currency recharge ratio.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function get(): array
    {
        return setting('currency', 'settings', [
            'recharge-ratio' => 1,
            'recharge-options' => '100,500,1000,2000,5000,10000',
            'recharge-max' => 10000000,
            'recharge-min' => 100,
            'cash-max' => 10000000,
            'cash-min' => 100,
        ]);
    }
}
