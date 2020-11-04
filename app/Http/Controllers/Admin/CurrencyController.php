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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CurrencyOrder as OrderModel;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Packages\Currency\Processes\Common;
use function Zhiyi\Plus\setting;

class CurrencyController extends Controller
{
    /**
     * 获取积分配置项.
     *
     * @return mixed
     */
    public function showConfig()
    {
        $cash = setting('currency', 'cash', [
            'rule' => '我是提现规则',
            'status' => true, // 提现开关
        ]);
        $recharge = setting('currency', 'recharge', [
            'rule' => '我是积分充值规则',
            'status' => true, // 充值开关
        ]);
        $config = [
            'basic_conf' => [
                'rule' => setting('currency', 'rule', '我是积分规则'),
                'cash.rule' => $cash['rule'],
                'cash.status' => $cash['status'],
                'recharge.rule' => $recharge['rule'],
                'recharge.status' => $recharge['status'],
            ],
            'detail_conf' => setting('currency', 'settings', [
                'recharge-ratio' => 1,
                'recharge-options' => '100,500,1000,2000,5000,10000',
                'recharge-max' => 10000000,
                'recharge-min' => 100,
                'cash-max' => 10000000,
                'cash-min' => 100,
            ]),
        ];

        return response()->json($config, 200);
    }

    /**
     * 更新积分基础配置.
     *
     * @param  Request $request
     * @return mixed
     */
    public function updateConfig(Request $request)
    {
        $type = strtolower((string) $request->query('type'));
        if ($type == 'detail') {
            setting('currency')->set('settings', [
                'recharge-ratio' => (int) $request->input('recharge-ratio'),
                'recharge-options' => $request->input('recharge-option'),
                'recharge-max' => $request->input('recharge-max'),
                'recharge-min' => $request->input('recharge-min'),
                'cash-max' => $request->input('cash-max'),
                'cash-min' => $request->input('cash-min'),
            ]);

            return response()->json(['message' => '更新成功'], 201);
        }

        setting('currency')->set([
            'rule' => $request->input('rule'),
            'recharge' => [
                'rule' => $request->input('recharge.rule'),
                'status' => $request->input('recharge.status'),
            ],
            'cash' => [
                'rule' => $request->input('cash.rule'),
                'status' => $request->input('cash.status'),
            ],
        ]);

        return response()->json(['message' => '更新成功'], 201);
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
    public function overview(OrderModel $orderModel)
    {
        $recharge = $orderModel->where('target_type', 'recharge')->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();
        $cash = $orderModel->where('target_type', 'cash')->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();

        return response()->json([
            'recharge' => $recharge,
            'cash' => $cash,
        ], 200);
    }

    /**
     * 获取用户积分信息.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = (int) $request->input('user');
        $limit = (int) $request->input('limit', 15);
        $offset = (int) $request->input('offset', 0);

        $query = (new User())->newQuery();

        $params = $request->only('name', 'email', 'phone');

        foreach ($params as $key => $value) {
            $query->when($value, function ($query) use ($key, $value) {
                return $query->where($key, 'like', sprintf('%%%s%%', $value));
            });
        }

        $query = $query->when($user, function ($query) use ($user) {
            return $query->where('id', $user);
        });

        $count = $query->count('id');
        $users = $query->with('currency')
        ->limit($limit)
        ->offset($offset)
        ->get()
        ->map(function ($item) {
            $item->setHidden(['password']);

            return $item;
        });

        return response()->json($users, 200, ['x-total' => $count]);
    }

    /**
     * 用户积分赋值.
     *
     * @param Request $request
     * @param Common  $common
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request, Common $common)
    {
        if (! is_numeric($request->input('num')) || $request->input('num') == 0) {
            return response()->json(['message' => '请输入正确的数值'], 422);
        }

        $num = (int) $request->input('num');

        $currency = User::findOrFail($request->input('user_id'))
        ->currency()
        ->firstOrCreate(['type' => 1], ['sum' => 0]);

        if ($num < 0 && $currency->sum < abs($num)) {
            return response()->json(['message' => '该用户积分不足不能进行减少操作'], 403);
        }

        $order = $common->createOrder($currency->owner_id, abs($num), ($num > 0 ? 1 : -1), '后台', '管理员操作');
        $order->save();

        $currency->sum += $num;
        $currency->save();

        return response()->json(['message' => '操作成功', 'currency' => $currency], 200);
    }
}
