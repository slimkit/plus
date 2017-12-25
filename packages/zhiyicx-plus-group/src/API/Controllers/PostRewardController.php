<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class PostRewardController
{
    // 系统货币名称
    protected $goldName;

    // 系统内货币与真实货币兑换比例
    protected $wallet_ratio;

    public function __construct(GoldType $goldModel, CommonConfig $configModel)
    {
        $walletConfig = $configModel->where('name', 'wallet:ratio')->first();

        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '金币';
        $this->wallet_ratio = $walletConfig ? $walletConfig->value : 100;
    }

    /**
     * 打赏操作.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @param WalletCharge $charge
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(Request $request, GroupPostModel $post, WalletCharge $charge, ConfigRepository $config)
    {
        if (! $config->get('plus-group.group_reward.status')) {
            return response()->json(['message' => ['打赏功能已关闭']], 422);
        }

        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => ['请输入正确的打赏金额'],
            ], 422);
        }
        $user = $request->user();
        $user->load('wallet');
        $post->load('user');
        $current_user = $post->user;

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => ['余额不足'],
            ], 403);
        }

        $user->getConnection()->transaction(function () use ($user, $post, $charge, $current_user, $amount) {
            // 扣除操作用户余额
            $user->wallet()->decrement('balance', $amount);

            // 扣费记录
            $userCharge = clone $charge;
            $userCharge->channel = 'user';
            $userCharge->account = $current_user->id;
            $userCharge->subject = '打赏帖子';
            $userCharge->action = 0;
            $userCharge->amount = $amount;
            $userCharge->body = sprintf('打赏帖子《%s》', $post->title);
            $userCharge->status = 1;
            $user->walletCharges()->save($userCharge);

            if ($current_user->wallet) {
                // 增加对应用户余额
                $current_user->wallet()->increment('balance', $amount);

                $charge->user_id = $current_user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->subject = '贴子被打赏';
                $charge->action = 1;
                $charge->amount = $amount;
                $charge->body = sprintf('帖子《%s》被打赏', $post->title);
                $charge->status = 1;
                $charge->save();

                // 添加被打赏通知
                $notice = sprintf('你的帖子《%s》被%s打赏%s%s', $post->title, $user->name, $amount * $this->wallet_ratio / 10000, $this->goldName);
                $post->addHidden('body');
                $current_user->sendNotifyMessage('group:post:reward', $notice, [
                    'post' => $post,
                    'user' => $user,
                ]);
            }

            // 打赏记录
            $post->rewards()->create([                
                'user_id' => $user->id,
                'target_user' => $current_user->id,
                'amount' => $amount
            ]);
        });

        return response()->json([
            'message' => ['打赏成功'],
        ], 201);
    }

    /**
     * 打赏用户列表.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, GroupPostModel $post)
    {
        $limit = max(1, min(30, $request->query('limit', 15)));
        $offset = $request->query('offset', 0);
        $order = in_array($order = $request->query('order', 'desc'), ['asc', 'desc']) ? $order : 'desc';
        $order_type = in_array($order_type = $request->query('order_type'), ['amount', 'date']) ? $order_type : 'date';
        $fieldMap = [
            'date' => 'id',
            'amount' => 'amount',
        ];
        $rewardables = $post->rewards()
            ->with('user')
            ->limit($limit)
            ->offset($offset)
            ->orderBy($fieldMap[$order_type], $order)
            ->get();

        return response()->json($rewardables, 200);
    }
}
