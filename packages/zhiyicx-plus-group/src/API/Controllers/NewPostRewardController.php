<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class NewPostRewardController
{
    /**
     * 元到分转换比列.
     */
    const RATIO = 100;

    /**
     * 打赏操作.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @param WalletCharge $charge
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(Request $request, GroupPostModel $post, TypeManager $manager, ConfigRepository $config)
    {
        if (! $config->get('plus-group.group_reward.status')) {
            return response()->json(['message' => ['打赏功能已关闭']], 422);
        }

        if ($post->user_id)
        $amount = (int) $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => ['请输入正确的打赏金额'],
            ], 422);
        }
        $user = $request->user();

        if ($post->user_id === $user->id) {
            return response()->json(['message' => ['不能打赏自己发布的帖子']], 422);
        }
        $user->load('wallet');
        $post->load('user');
        $target = $post->user;

        if (! $user->newWallet || $user->newWallet->balance < $amount) {
            return response()->json(['message' => ['余额不足']], 403);
        }

        // 记录订单
        $money = $amount / self::RATIO;

        $status = $manager->driver(Order::TARGET_TYPE_REWARD)->reward([
            'reward_resource' => $user,
            'order' => [
                'user' => $user,
                'target' => $target,
                'amount' => $amount,
                'user_order_body' => sprintf('打赏帖子《%s》，钱包扣除%s元', $post->title, $money),
                'target_order_body' => sprintf('帖子《%s》被打赏，钱包增加%s元', $post->title, $money),
            ],
            'notice' => [
                'type' => 'group:post:reward',
                'detail' => ['user' => $user, 'post' => $post],
                'message' => sprintf('你的帖子《%s》被用户%s打赏%s元', $post->title, $user->name, $money),
            ],
        ]);

        if ($status === true) {
            return response()->json(['message' => ['打赏成功']], 201);
        } else {
            return response()->json(['message' => ['打赏失败']], 500);
        }
    }
}
