<?php

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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;

class NewUserRewardController extends Controller
{
    /**
     * 打赏用户.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  User         $target
     * @return json
     */
    public function store(Request $request, User $target, TypeManager $manager)
    {
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => ['请输入正确的打赏金额'],
            ], 422);
        }
        $user = $request->user();
        $user->load('wallet');

        if ($user->id == $target->id) {
            return response()->json(['message' => ['用户不能打赏自己']], 403);
        }

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => ['余额不足'],
            ], 403);
        }

        if (! $target->wallet) {
            return response()->json([
                'message' => ['对方钱包信息有误'],
            ], 500);
        }

        // 记录订单
        $money = ($amount / 100);

        $status = $manager->driver(Order::TARGET_TYPE_REWARD)->reward([
            'reward_resource' => $user,
            'order' => [
                'user' => $user,
                'target' => $target,
                'amount' => $amount,
                'user_order_body' => sprintf('打赏用户%s，钱包扣除%s元', $target->name, $money),
                'target_order_body' => sprintf('被用户%s打赏，钱包增加%s元', $user->name, $money),
            ],
            'notice' => [
                'type' => 'user:reward',
                'detail' => ['user' => $user],
                'message' => sprintf('你被%s打赏%s', $user->name, $money),
            ],
        ]);

        if ($status === true) {
            return response()->json(['message' => ['打赏成功']], 201);
        } else {
            return response()->json(['message' => ['打赏失败']], 500);
        }
    }
}
