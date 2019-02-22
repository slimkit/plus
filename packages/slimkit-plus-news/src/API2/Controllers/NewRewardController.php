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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Zhiyi\Plus\Notifications\System as SystemNotification;

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
    public function reward(Request $request, News $news, UserProcess $processer, GoldType $goldModel)
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
            return response()->json(['message' => '余额不足'], 403);
        }

        // 记录订单
        $pay = $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏“%s”的资讯', $target->name, $news->title, $amount), sprintf('打赏“%s”的资讯“%s”，%s扣除%s', $target->name, $news->title, $goldName, $amount));
        $paid = $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你的资讯', $target->name), sprintf('“%s”打赏了你的的资讯“%s”，%s扣除%s', $user->name, $news->title, $goldName, $amount));

        if ($pay && $paid) {
            $target->notify(new SystemNotification(sprintf('%s打赏了你的资讯文章', $user->name), [
                'type' => 'reward:news',
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'amount' => $amount,
                'unit' => $goldName,
                'news' => [
                    'id' => $news->id,
                    'title' => $news->title,
                ]
            ]));

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
