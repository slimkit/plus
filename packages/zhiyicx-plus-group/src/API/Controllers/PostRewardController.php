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

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class PostRewardController
{
    // 系统货币名称
    protected $goldName;

    // 系统内货币与真实货币兑换比例
    protected $wallet_ratio;

    public function __construct(GoldType $goldModel)
    {
        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
    }

    /**
     * 打赏操作.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @param UserProcess $process
     * @param   ConfigRepository $config
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(Request $request, GroupPostModel $post, UserProcess $process, ConfigRepository $config)
    {
        if (! $config->get('plus-group.group_reward.status')) {
            return response()->json(['message' => '打赏功能已关闭'], 422);
        }

        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => '请输入正确的'.$this->goldName.'数量',
            ], 422);
        }
        $user = $request->user();
        $post->load('user');
        $current_user = $post->user;

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => $this->goldName.'不足',
            ], 403);
        }

        $user->getConnection()->transaction(function () use ($user, $post, $current_user, $amount, $process) {
            if ($current_user->wallet) {
                $process->prepayment($user->id, $amount, $current_user->id, sprintf('打赏“%s”的帖子', $current_user->name, $post->title, $amount), sprintf('打赏“%s”的帖子，%s扣除%s', $current_user->name, $this->goldName, $amount));
                $process->receivables($current_user->id, $amount, $user->id, sprintf('“%s”打赏了你的帖子', $user->name), sprintf('“%s”打赏了你的帖子，%s增加%s', $user->name, $this->goldName, $amount));
                // 添加被打赏通知
                $notice = sprintf('你的帖子《%s》被%s打赏%s%s', $post->title, $user->name, $amount, $this->goldName);
                // 1.8启用, 新版未读消息提醒
                $userCount = UserCountModel::firstOrNew([
                    'type' => 'user-system',
                    'user_id' => $current_user->id
                ]);
                $userCount->total += 1;
                $userCount->save();
                $post->addHidden('body');
                $current_user->sendNotifyMessage('group:post:reward', $notice, [
                    'post' => $post,
                    'user' => $user,
                ]);
                // 打赏记录
                $post->rewards()->create([
                    'user_id' => $user->id,
                    'target_user' => $current_user->id,
                    'amount' => $amount,
                ]);
            }
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
