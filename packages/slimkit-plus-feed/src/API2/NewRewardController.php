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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class NewRewardController extends Controller
{
    /**
     * 打赏一条动态.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Feed $feed
     * @param  WalletCharge $charge
     * @return mix
     */
    public function reward(Request $request, Feed $feed, TypeManager $manager)
    {
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => ['请输入正确的打赏金额'],
            ], 422);
        }
        $user = $request->user();
        $user->load('wallet');
        $feed->load('user');
        $target = $feed->user;

        if ($user->id == $target->id) {
            return response()->json(['message' => ['不能打赏自己的发布的动态']], 403);
        }

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => ['余额不足'],
            ], 403);
        }

        $feedTitle = str_limit($feed->feed_content, 100, '...');
        $money = $amount / 100;

        // 记录订单
        $status = $manager->driver(Order::TARGET_TYPE_REWARD)->reward([
            'reward_resource' => $feed,
            'order' => [
                'user' => $user,
                'target' => $target,
                'amount' => $amount,
                'user_order_body' => sprintf('打赏动态《%s》，钱包扣除%s元', $feedTitle, $money),
                'target_order_body' => sprintf('动态《%s》被打赏，钱包增加%s元', $feedTitle, $money),
            ],
            'notice' => [
                'type' => 'feed:reward',
                'detail' => ['feed' => $feed, 'user' => $user],
                'message' => sprintf('你的《%s》动态被用户%s打赏%s元', $feedTitle, $user->name, $money),
            ],
        ]);

        if ($status === true) {
            return response()->json(['message' => ['打赏成功']], 201);
        } else {
            return response()->json(['message' => ['打赏失败']], 500);
        }
    }

    /**
     * 一条动态的打赏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Feed $feed
     * @return mix
     */
    public function index(Request $request, Feed $feed)
    {
        $limit = max(1, min(30, $request->query('limit', 15)));
        $since = $request->query('since', 0);
        $offset = $request->query('offset', 0);
        $order = in_array($order = $request->query('order', 'desc'), ['asc', 'desc']) ? $order : 'desc';
        $order_type = in_array($order_type = $request->query('order_type'), ['amount', 'date']) ? $order_type : 'date';
        $fieldMap = [
            'date' => 'id',
            'amount' => 'amount',
        ];
        $rewardables = $feed->rewards()
            ->with('user')
            ->when($since, function ($query) use ($since, $order, $order_type, $fieldMap) {
                return $query->where($fieldMap[$order_type], $order === 'asc' ? '>' : '<', $since);
            })
            ->limit($limit)
            ->offset($offset)
            ->orderBy($fieldMap[$order_type], $order)
            ->get();

        return response()->json($rewardables, 200);
    }
}
