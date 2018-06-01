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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewRewardController extends Controller
{
    /**
     * 元到分转换比列.
     */
    const RATIO = 100;

    /**
     * 打赏一条资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News $news
     * @return mix
     */
    public function reward(Request $request, News $news, UserProcess $processer, GoldType $goldModel, CommonConfig $configModel)
    {
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        $amount = (int) $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json(['amount' => '请输入正确的'.$goldName.'数量'], 422);
        }
        $user = $request->user();
        $target = $news->user;
        if ($user->id == $target->id) {
            return response()->json(['message' => '不能打赏自己的发布的资讯'], 422);
        }
        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => $goldName.'不足'], 403);
        }

        // 记录订单
        $pay = $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏“%s”的资讯', $target->name, $news->title, $amount), sprintf('打赏“%s”的资讯“%s”，%s扣除%s', $target->name, $news->title, $goldName, $amount));
        $paid = $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你的资讯', $target->name), sprintf('“%s”打赏了你的的资讯“%s”，%s扣除%s', $user->name, $news->title, $goldName, $amount));

        if ($pay && $paid) {
            $target->sendNotifyMessage('news:reward', sprintf('“%s”打赏了你的资讯', $user->name), [
                'feed' => $news,
                'user' => $user,
            ]);
            // 增加被打赏未读数
            $userUnreadCount = $target->unreadNotifications()
                ->count();
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $target->id,
            ]);

            $userCount->total = $userUnreadCount;
            $userCount->save();
            // 打赏记录
            $news->reward($user, $amount);

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
