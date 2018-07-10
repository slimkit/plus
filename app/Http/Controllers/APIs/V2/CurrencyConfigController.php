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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Repository\CurrencyConfig;

class CurrencyConfigController extends Controller
{
    /**
     * 获取积分相关配置.
     *
     * @param CurrencyConfig $config
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function show(CurrencyConfig $config)
    {
        $configs = array_merge($config->get(), [
            'rule' => config('currency.rule', ''),
            'recharge-rule' => config('currency.recharge.rule', ''),
            'cash' => ($cash = CommonConfig::where('name', 'cash')->where('namespace', 'wallet')->first()) ? json_decode($cash->value) : [],
            'cash-rule' => config('currency.cash.rule', ''),
            'apple-IAP-rule' => config('currency.recharge.IAP.rule', ''),
            'recharge-type' => ($recharge_type = CommonConfig::where('name', 'wallet:recharge-type')->where('namespace', 'common')->first()) ? json_decode($recharge_type->value) : [],
        ]);

        return response()->json($configs, 200);
    }
}
