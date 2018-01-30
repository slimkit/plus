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

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Repository\CurrencyConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CurrencyOrder as OrderModel;

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
        $type = (string) $request->query('type');

        if ($type == 'detail') {
            foreach ($request->except('type') as $key => $value) {
                $config = CommonConfig::where('name', sprintf('currency:%s', $key))
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
        $bootstrappers['cash.rule'] = config('currency.cash.rule', null);
        $bootstrappers['cash.status'] = config('currency.cash.status', true);
        $bootstrappers['recharge.rule'] = config('currency.recharge.rule', null);
        $bootstrappers['recharge.status'] = config('currency.recharge.status', true);

        return $bootstrappers;
    }

    /**
     * 积分流水.
     *
     * @param Request $request
     * @param OrderModel $orderModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function list(Request $request, OrderModel $orderModel)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);
        $user = (int) $request->query('user');
        $name = $request->query('name');
        $action = $request->query('action');
        $state = $request->query('state');

        $query = $orderModel->when($user, function ($query) use ($user) {
            return $query->where('owner_id', $user);
        })
        ->when($name, function ($query) use ($name) {
            return $query->whereHas('user', function ($query) use ($name) {
                return $query->where('name', 'like', '%'.$name.'%');
            });
        })
        ->when(in_array($action, [1, -1]), function ($query) use ($action) {
            return $query->where('type', $action);
        })
        ->when(! is_null($state), function ($query) use ($state) {
            return $query->where('state', $state);
        });

        $count = $query->count();
        $orders = $query->with('user')
        ->limit($limit)
        ->offset($offset)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($orders, 200, ['x-total' => $count]);
    }

    /**
     * 积分概览.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function overview(Request $request, OrderModel $orderModel)
    {
        $recharge = $orderModel->where('target_type', 'recharge')->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();
        $cash = $orderModel->where('target_type', 'cash')->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();

        return response()->json([
            'recharge' => $recharge,
            'cash' => $cash,
        ], 200);
    }
}
