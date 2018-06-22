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
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewPostRewardController
{
    /**
     * 打赏操作.
     *
     * @param Request          $request
     * @param GroupPostModel   $post
     * @param UserProcess      $process
     * @param ConfigRepository $config
     * @param GoldType         $goldModel
     * @return mixed
     * @throws \Exception
     * @author BS <414606094@qq.com>
     */
    public function store(Request $request, GroupPostModel $post, UserProcess $process, ConfigRepository $config, GoldType $goldModel)
    {
        if (! $config->get('plus-group.group_reward.status')) {
            return response()->json(['message' => '打赏功能已关闭'], 422);
        }
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        if ($post->user_id) {
            $amount = (int) $request->input('amount');
        }
        if (! $amount || $amount < 0) {
            return response()->json([
                'message' => '请输入正确的'.$goldName.'数量',
            ], 422);
        }
        $user = $request->user();

        if ($post->user_id === $user->id) {
            return response()->json(['message' => '不能打赏自己发布的帖子'], 422);
        }
        $post->load('user');
        $target = $post->user;

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => $goldName.'不足'], 403);
        }
        $pay = $process->prepayment($user->id, $amount, $target->id, sprintf('打赏“%s”的帖子', $target->name, $post->title, $amount), sprintf('打赏“%s”的帖子，%s扣除%s', $target->name, $goldName, $amount));

        $paid = $process->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你的帖子', $user->name), sprintf('“%s”打赏了你的帖子，%s增加%s', $user->name, $goldName, $amount));

        if ($pay && $paid) {
            $notice = sprintf('“%s”打赏了你的帖子', $user->name);
            $target->sendNotifyMessage('group:post:reward', $notice, [
                'post' => $post,
                'user' => $user,
            ]);
            // 1.8启用, 新版未读消息提醒
            $userUnreadCount = $target->unreadNotifications()
                ->count();
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $target->id
            ]);
            $userCount->total = $userUnreadCount;
            $userCount->save();
            // 打赏记录
            $post->rewards()->create([
                'user_id' => $user->id,
                'target_user' => $target->id,
                'amount' => $amount,
            ]);
            return response()->json(['message' => '打赏成功'], 201);
        } else {
            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
