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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Repository\CurrencyConfig;
use Zhiyi\Plus\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    protected $rep;

    public function __construct(CurrencyConfig $config)
    {
        $this->rep = $config;
    }

    /**
     * 获取积分配置项.
     *
     * @param  CurrencyConfig $config
     * @return mixed
     */
    public function showConfig()
    {
        $config = [];
        $config['basic_conf'] = $this->basicConfig();
        $config['detail_conf'] = $this->rep->get();

        return response()->json($config, 200);
    }

    /**
     * 更新积分基础配置.
     *
     * @param  Request $request
     * @return mixed
     */
    public function updateConfig(Request $request, Configuration $configuration)
    {
        $type = (string) $request->input('type');

        if ($type == 'detail') {
            foreach ($request->all() as $key => $value) {
                $field = $key == 'recharge-options' ? 'recharge-option' : $key;

                $config = CommonConfig::where('name', sprintf('currency:%s', $field))
                ->where('namespace', 'currency')
                ->first();

                $config->value = $value;
                $config->save();
            }
            $this->rep->flush();
        } else {
            $data = $request->all();

            $config = $configuration->getConfiguration();
            $config->set('currency.rule', (string) $data['rule']);
            $config->set('currency.cash.rule', (string) $data['cash']['rule']);
            $config->set('currency.cash.status', (bool) $data['cash']['status']);
            $config->set('currency.recharge.rule', (string) $data['recharge']['rule']);
            $config->set('currency.recharge.status', (bool) $data['recharge']['status']);

            $configuration->save($config);
        }

        return response()->json(['message' => '更新成功'], 201);
    }

    /**
     * 积分开关相关配置数据.
     *
     * @return array
     */
    protected function basicConfig(): array
    {
        $bootstrappers = [];

        $bootstrappers['rule'] = config('currency.rule', null);
        $bootstrappers['cash.rule'] =  config('currency.cash.rule', null);
        $bootstrappers['cash.status'] =  config('currency.cash.status', true);
        $bootstrappers['recharge.rule'] = config('currency.recharge.rule', null);
        $bootstrappers['recharge.status'] = config('currency.recharge.status', true);

        return $bootstrappers;
    }
}


















