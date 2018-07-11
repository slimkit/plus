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

use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\Plus\Http\Requests\API2\StoreTransform;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Http\Requests\API2\NewStoreWalletRecharge;

class NewWalletRechargeController extends Controller
{
    protected $type = [
        'income' => Order::TYPE_INCOME,
        'expenses' => Order::TYPE_EXPENSES,
    ];

    /**
     * 钱包流水列表.
     *
     * @param Request $request
     * @param WalletOrderModel $walletOrderModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function list(Request $request, WalletOrderModel $walletOrderModel)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $action = $request->query('action');
        $user = $request->user();
        $orders = $walletOrderModel->where('owner_id', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->when(in_array($action, ['income', 'expenses']), function ($query) use ($action) {
                return $query->where('type', $this->type[$action]);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($orders, 200);
    }

    /**
     * 创建充值订单.
     *
     * @param NewStoreWalletRecharge $request
     * @param TypeManager $manager
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(NewStoreWalletRecharge $request, TypeManager $manager)
    {
        $user = $request->user();
        $amount = (int) $request->input('amount');
        $extra = $request->input('extra', []);
        $type = $request->input('type');

        if (($result = $manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->create($user, $amount, $type, $extra)) !== false) {
            return response()->json($result, 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * 充值回调通知.
     *
     * @param Request $request
     * @param TypeManager $manager
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function webhook(Request $request, TypeManager $manager)
    {
        if ($manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->webhook($request) === true) {
            return response('通知成功');
        }

        return response('操作失败', 500);
    }

    /**
     * 主动取回凭据.
     *
     * @param WalletOrderModel $walletOrder
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function retrieve(WalletOrderModel $order, TypeManager $manager)
    {
        if ($manager->driver(Order::TARGET_TYPE_RECHARGE_PING_P_P)->retrieve($order) === true) {
            return response()->json($order, 200);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }

    /**
     * 创建转换积分订单.
     *
     * @param Request $request
     * @param TypeManager $manager
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function transform(StoreTransform $request, TypeManager $manager)
    {
        $user = $request->user();
        $amount = (int) $request->input('amount');

        if ($manager->driver(Order::TARGET_TYPE_TRANSFORM)->transform($user, $amount) === true) {
            return response()->json(['message' => ['操作成功']], 201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
