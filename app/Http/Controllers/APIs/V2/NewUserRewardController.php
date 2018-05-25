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
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewUserRewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    public function __construct(GoldType $goldModel, CommonConfig $configModel)
    {
        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '积分';
    }

    /**
     * 新版打赏用户.
     *
     * @param Request $request
     * @param User $target
     * @param TypeManager $manager
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, User $target, UserProcess $processer)
    {
        $amount = (int) $request->input('amount');

        if (! $amount || $amount < 0) {
            return response()->json(['amount' => '请输入正确的'.$this->goldName.'数量'], 422);
        }

        $user = $request->user();

        if ($user->id == $target->id) {
            return response()->json(['message' => '用户不能打赏自己'], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => $this->goldName.'不足'], 403);
        }

        if (! $target->currency) {
            return response()->json(['message' => '对方'.$this->goldName.'信息有误'], 500);
        }

        $userUnreadCount = $target->unreadNotifications()
            ->count();
        $userCount = UserCount::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target->id,
        ]);
        $userCount->total = $userUnreadCount + 1;

        $pay = $processer->prepayment($user->id, $amount, $target->id, sprintf('打赏用户“%s”', $target->name), sprintf('打赏用户“%s”，%s扣除%s', $target->name, $this->goldName, $amount));
        $paid = $processer->receivables($target->id, $amount, $user->id, sprintf('“%s”打赏了你', $user->name), sprintf('用户“%s”打赏了你”，%s增加%s', $user->name, $this->goldName, $amount));

        if ($user->id !== $target->id) {
            $userCount->save();
        }

        if ($pay && $paid) {
            // 添加被打赏通知
            $targetNotice = sprintf('“%s”打赏了你%s%s', $user->name, $amount, $this->goldName);
            $target->sendNotifyMessage('user:reward', $targetNotice, [
                'user' => $user,
            ]);
            $userCount->save();

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
