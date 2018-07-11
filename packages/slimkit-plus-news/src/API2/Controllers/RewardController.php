<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class RewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    // 系统内货币与真实货币兑换比例
    protected $wallet_ratio;

    public function __construct(GoldType $goldModel, CommonConfig $configModel)
    {
        $walletConfig = $configModel->where('name', 'wallet:ratio')->first();

        $this->goldName = $goldModel->where('status', 1)->select('name', 'unit')->value('name') ?? '金币';
        $this->wallet_ratio = $walletConfig->value ?? 100;
    }

    /**
     * 打赏一条资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  News         $news
     * @param  WalletCharge $charge
     * @return mix
     */
    public function reward(Request $request, News $news, WalletCharge $charge)
    {
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => '请输入正确的打赏金额',
            ], 422);
        }
        $user = $request->user();
        $user->load('wallet');
        $news->load('user');
        $targetUser = $news->user;

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => '余额不足',
            ], 403);
        }

        // 系统消息未读数预处理, 事务中只做保存操作
        $userUnreadCount = $targetUser->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
            'user_id' => $targetUser->id,
            'type' => 'user-system',
        ]);

        $userCount->total = $userUnreadCount + 1;
        $user->getConnection()->transaction(function () use ($user, $news, $charge, $targetUser, $amount, $userCount) {
            // 扣除操作用户余额
            $user->wallet()->decrement('balance', $amount);

            // 扣费记录
            $userCharge = clone $charge;
            $userCharge->channel = 'user';
            $userCharge->account = $targetUser->id;
            $userCharge->subject = '资讯打赏';
            $userCharge->action = 0;
            $userCharge->amount = $amount;
            $userCharge->body = sprintf('打赏资讯"%s"', $news->title);
            $userCharge->status = 1;
            $user->walletCharges()->save($userCharge);

            if ($targetUser->wallet) {
                // 旧版钱包增加对应用户余额
                $targetUser->wallet()->increment('balance', $amount);

                $charge->user_id = $targetUser->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->subject = '资讯被打赏';
                $charge->action = 1;
                $charge->amount = $amount;
                $charge->body = sprintf('资讯"%s"被打赏', $news->title);
                $charge->status = 1;
                $charge->save();

                // 添加被打赏通知
                $currentNotice = sprintf('你的资讯"%s"被%s打赏%s%s', $news->title, $user->name, $amount * $this->wallet_ratio / 10000, $this->goldName);
                $targetUser->sendNotifyMessage('news:reward', $currentNotice, [
                    'news' => $news,
                    'user' => $user,
                ]);
                // 保存系统未读数
                $userCount->save();
            }
            // 打赏记录
            $news->reward($user, $amount);
        });

        return response()->json([
            'message' => '打赏成功',
        ], 201);
    }

    /**
     * 一条资讯的打赏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @return mix
     */
    public function index(Request $request, News $news)
    {
        $limit = max(1, min(30, $request->query('limit', 15)));
        $since = $request->query('since', 0);
        $offset = $request->query('offset', 0);
        $order = in_array($order = $request->query('order', 'desc'), ['asc', 'desc']) ? $order : 'desc';
        $order_type = in_array($order_type = $request->query('order_type'), ['amount', 'date']) ? $order_type : 'date';
        $fieldMap = [
            'date' => 'id',
            'amount' => 'amount',
        ];
        $rewardables = $news->rewards()
            ->with('user')
            ->when($since, function ($query) use ($since, $order, $order_type, $fieldMap) {
                return $query->where($fieldMap[$order_type], $order === 'asc' ? '>' : '<', $since);
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($fieldMap[$order_type], $order)
            ->get();

        return response()->json($rewardables, 200);
    }

    /**
     * 查看一条资讯的打赏统计
     *
     * @author bs<414606094@qq.com>
     * @param  News   $news
     * @return array
     */
    public function sum(News $news)
    {
        return response()->json($news->rewardCount(), 200);
    }
}
