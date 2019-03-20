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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewRewardController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this
            ->middleware(VerifyUserPassword::class)
            ->only(['reward']);
    }

    /**
     * 打赏一条动态.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Feed $feed
     * @param  WalletCharge $charge
     * @return mix
     */
    public function reward(Request $request, Feed $feed, UserProcess $process, GoldType $goldModel)
    {
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        $goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
        $amount = (int) $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => '请输入正确的'.$goldName.'数量',
            ], 422);
        }

        $user = $request->user();
        $target = $feed->user;

        if ($user->id == $target->id) {
            return response()->json(['message' => '不能打赏自己的发布的动态'], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json([
                'message' => '余额不足',
            ], 403);
        }

        $feedTitle = str_limit($feed->feed_content, 100, '...');
        $feedTitle = ($feedTitle ? "“${feedTitle}”" : '');

        $pay = $process->prepayment($user->id, $amount, $target->id, sprintf('打赏“%s”的动态', $target->name, $feedTitle, $amount), sprintf('打赏“%s”的动态，%s扣除%s', $target->name, $goldName, $amount));
        $paid = $process->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你的动态', $user->name), sprintf('“%s”打赏了你的动态，%s增加%s', $user->name, $goldName, $amount));
        if ($pay && $paid) {
            // 打赏记录
            $feed->reward($user, $amount);
            $feed->user->notify(new SystemNotification(sprintf('%s打赏了你的动态', $user->name), [
                'type' => 'reward:feeds',
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'amount' => $amount,
                'unit' => $goldName,
                'feed_id' => $feed->id,
            ]));

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
