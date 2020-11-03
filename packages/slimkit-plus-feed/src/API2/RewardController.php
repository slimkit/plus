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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheNames;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\CurrencyType;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Notifications\System as SystemNotification;

class RewardController extends Controller
{
    // 系统货币名称
    protected $goldName;

    // 系统内货币与真实货币兑换比例
    protected $wallet_ratio;

    public function __construct(CommonConfig $configModel)
    {
        $walletConfig = $configModel->where('name', 'wallet:ratio')->first();

        $this->goldName = CurrencyType::current('name');
        $this->wallet_ratio = $walletConfig ? $walletConfig->value : 100;
    }

    /**
     * 打赏一条动态.
     *
     * @param  Request  $request
     * @param  Feed  $feed
     * @param  WalletCharge  $charge
     *
     * @return mix
     * @throws \Throwable
     * @author bs<414606094@qq.com>
     */
    public function reward(Request $request, Feed $feed, WalletCharge $charge)
    {
        $user = $request->user();
        // 判断锁
        if (Cache::has(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
            $user->id))) {
            return response('请求太频繁了', 429);
        }
        // 加锁
        Cache::forever(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
            $user->id), true);
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json([
                'amount' => '请输入正确的打赏金额',
            ], 422);
        }
        $user->load('wallet');
        $feed->load('user');
        $current_user = $feed->user;
        if (! $user->wallet || $user->wallet->balance < $amount) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json([
                'message' => '余额不足',
            ], 403);
        }

        try {
            $user->getConnection()->transaction(function () use (
                $user,
                $feed,
                $charge,
                $current_user,
                $amount
            ) {
                // 扣除操作用户余额
                $user->wallet()->decrement('balance', $amount);

                $feed_title = Str::limit($feed->feed_content, 100, '...');
                $feed_title = $feed_title ? "“${feed_title}”" : '';
                // 扣费记录
                $userCharge = clone $charge;
                $userCharge->channel = 'user';
                $userCharge->account = $current_user->id;
                $userCharge->subject = '打赏动态';
                $userCharge->action = 0;
                $userCharge->amount = $amount;
                $userCharge->body = sprintf('打赏“%s”的动态%s', $current_user->name,
                    $feed_title);
                $userCharge->status = 1;
                $user->walletCharges()->save($userCharge);
                // 打赏记录
                $feed->reward($user, $amount);

                if ($current_user->wallet) {
                    // 增加对应用户余额
                    $current_user->wallet()->increment('balance', $amount);

                    $charge->user_id = $current_user->id;
                    $charge->channel = 'user';
                    $charge->account = $user->id;
                    $charge->subject = '动态被打赏';
                    $charge->action = 1;
                    $charge->amount = $amount;
                    $charge->body = sprintf('“%s”打赏了你的动态%s', $user->name,
                        $feed_title);
                    $charge->status = 1;
                    $charge->save();

                    // 添加被打赏通知

                    $feed->user->notify(new SystemNotification(sprintf('%s打赏了你的动态',
                        $user->name), [
                            'type'    => 'reward:feeds',
                            'sender'  => [
                                'id'   => $user->id,
                                'name' => $user->name,
                            ],
                            'amount'  => $amount,
                            'unit'    => $this->goldName,
                            'feed_id' => $feed->id,
                        ]));
                }
            });
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json([
                'message' => '打赏成功',
            ], 201);
        } catch (\Throwable $e) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));
            throw $e;
        }
    }

    /**
     * 一条动态的打赏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Feed    $feed
     * @return mix
     */
    public function index(Request $request, Feed $feed)
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
        $rewardables = $feed->rewards()
            ->with('user')
            ->when($since, function ($query) use ($since, $order, $order_type, $fieldMap) {
                return $query->where($fieldMap[$order_type], $order === 'asc' ? '>' : '<', $since);
            })
            ->limit($limit)
            ->offset($offset)
            ->orderBy($fieldMap[$order_type], $order)
            ->get();

        return response()->json($rewardables, 200);
    }
}
