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
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;

class UserRewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    // 系统内货币与真实货币兑换比例
    protected $wallet_ratio;

    public function __construct(GoldType $goldModel, CommonConfig $configModel)
    {
        $walletConfig = $configModel->where('name', 'wallet:ratio')->first();

        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '金币';
        $this->wallet_ratio = $walletConfig->value ?? 100;
    }

    /**
     * 打赏用户.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  User         $target
     * @param  WalletCharge $chargeModel
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
        $status = $manager->driver(Order::TARGET_TYPE_REWARD)->reward($user, $target, $amount, [
            'reward_resource' => $user,
            'target_user' => $target,
            'reward_type' => 'user:reward',
            'reward_notice' => sprintf('你被%s打赏%s%s', $user->name, $amount / 100, $this->goldName),
            'reward_detail' => [
                'user' => $user,
            ],
        ]);

        if ($status === true) {
            return response()->json(['message' => ['打赏成功']], 201);
        } else {
            return response()->json(['message' => ['打赏失败']], 500);
        }
    }
}
