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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CurrencyType;
use Zhiyi\Plus\Models\GoldType;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class UserRewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    public function __construct()
    {
        $this->goldName = CurrencyType::current('name');
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
        $amount = (int) $request->input('amount');
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

        $user->getConnection()->transaction(function () use ($user, $target, $amount, $processer) {
            $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏用户“%s”', $target->name), sprintf('打赏用户“%s”，积分扣除%s', $target->name, $amount));
            $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你', $user->name), sprintf('用户“%s”打赏了你”，积分增加%s', $user->name, $amount));

            // 打赏记录
            $target->reward($user, $amount);
        });

        if ($user->id !== $target->id) {
            $target->notify(new SystemNotification(sprintf('%s打赏了你%s%s', $user->name, $amount, $this->goldName), [
                'type' => 'reward',
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'amount' => $amount,
                'unit' => $this->goldName,
            ]));
        }

        return response()->json([
            'message' => '打赏成功',
        ], 201);
    }
}
