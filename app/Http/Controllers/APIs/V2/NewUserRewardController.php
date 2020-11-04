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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\CacheNames;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Models\CurrencyType;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewUserRewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    public function __construct()
    {
        $this
            ->middleware(VerifyUserPassword::class)
            ->only(['store']);
        $this->goldName = CurrencyType::current('name');
    }

    /**
     * 新版打赏用户.
     *
     * @param  Request  $request
     * @param  User  $target
     * @param  UserProcess  $processer
     *
     * @return JsonResponse
     */
    public function store(Request $request, User $target, UserProcess $processer)
    {
        $user = $request->user();

        // 判断锁
        if (Cache::has(sprintf(CacheNames::REWARD_USER_LOCK, $target->id, $user->id))) {
            return response('操作太频繁了', 429);
        }
        // 加锁
        Cache::forever(sprintf(CacheNames::REWARD_USER_LOCK, $target->id, $user->id), true);

        $amount = (int) $request->input('amount');

        if (! $amount || $amount < 0) {
            return response()->json(['amount' => '请输入正确的'.$this->goldName.'数量'], 422);
        }

        if ($user->id == $target->id) {
            return response()->json(['message' => '用户不能打赏自己'], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => '余额不足'], 403);
        }

        if (! $target->currency) {
            return response()->json(['message' => '对方'.$this->goldName.'信息有误'], 500);
        }

        $pay = $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏用户“%s”', $target->name), sprintf('打赏用户“%s”，%s扣除%s', $target->name, $this->goldName, $amount));
        $paid = $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你', $user->name), sprintf('用户“%s”打赏了你”，%s增加%s', $user->name, $this->goldName, $amount));

        if ($pay && $paid) {
            $target->notify(new SystemNotification(sprintf('%s打赏了你%s%s', $user->name, $amount, $this->goldName), [
                'type' => 'reward',
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'amount' => $amount,
                'unit' => $this->goldName,
            ]));
            Cache::forget(sprintf(CacheNames::REWARD_USER_LOCK, $target->id, $user->id));

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            Cache::forget(sprintf(CacheNames::REWARD_USER_LOCK, $target->id, $user->id));

            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
