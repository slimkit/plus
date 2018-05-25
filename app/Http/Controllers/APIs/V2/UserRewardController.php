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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\UserCount;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class UserRewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    public function __construct(GoldType $goldModel, CommonConfig $configModel)
    {
        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
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
    public function store(Request $request, User $target, UserProcess $processer)
    {
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => '请输入正确的打赏数量',
            ], 422);
        }
        $user = $request->user();

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json([
                'message' => '积分不足',
            ], 403);
        }

        if (! $target->currency) {
            return response()->json([
                'message' => '对方积分信息有误',
            ], 500);
        }

        $userUnreadCount = $target->unreadNotifications()
            ->count();
        $userCount = UserCount::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target->id,
        ]);
        $userCount->total = $userUnreadCount + 1;
        $user->getConnection()->transaction(function () use ($user, $target, $amount, $userCount, $processer) {
            $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏用户“%s”', $target->name), sprintf('打赏用户“%s”，积分扣除%s', $target->name, $amount));
            $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你', $user->name), sprintf('用户“%s”打赏了你”，积分增加%s', $user->name, $amount));

            if ($user->id !== $target->id) {
                // 添加被打赏通知
                $targetNotice = sprintf('“%s”打赏了你%s%s', $user->name, $amount, $this->goldName);
                $target->sendNotifyMessage('user:reward', $targetNotice, [
                    'user' => $user,
                ]);
                $userCount->save();
            }

            // 打赏记录
            $target->reward($user, $amount);
        });

        return response()->json([
            'message' => '打赏成功',
        ], 201);
    }
}
