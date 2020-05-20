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

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheNames;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Models\CurrencyType;
use Zhiyi\Plus\Notifications\System as SystemNotification;
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
     * @param  Request  $request
     * @param  int  $feed
     * @param  UserProcess  $process
     *
     * @return mix
     * @throws \Exception
     * @author bs<414606094@qq.com>
     */
    public function reward(Request $request, int $feed, UserProcess $process)
    {
        $user = $request->user();
        // 判断锁
        if (Cache::has(sprintf(CacheNames::REWARD_FEED_LOCK, $feed,
            $user->id))
        ) {
            return response('请求太频繁了', 429);
        }
        // 加锁
        Cache::forever(sprintf(CacheNames::REWARD_FEED_LOCK, $feed,
            $user->id), true);

        $goldName = CurrencyType::current('name');
        $amount = (int) $request->input('amount');
        if (! $amount || $amount < 0) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed,
                $user->id));

            return response()->json([
                'amount' => '请输入正确的'.$goldName.'数量',
            ], 422);
        }
        $feed = Feed::query()->withoutGlobalScopes([
            'images', 'topics', 'paidNode', 'video',
        ])
            ->with([
                'user' => function (BelongsTo $belongs_to) {
                    $belongs_to->withoutGlobalScope('certification')
                        ->with('currency');
                },
            ])
            ->find($feed);

        $target = $feed->user;

        if ($user->id === $target->id) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json(['message' => '不能打赏自己的发布的动态'], 422);
        }

        if (! $user->currency || $user->currency->sum < $amount) {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json([
                'message' => '余额不足',
            ], 403);
        }

        $feedTitle = Str::limit($feed->feed_content, 100, '...');
        $feedTitle = ($feedTitle ? "“${feedTitle}”" : '');

        $pay = $process->prepayment($user, $amount, $target->id,
            sprintf('打赏“%s”的动态', $target->name, $feedTitle, $amount),
            sprintf('打赏“%s”的动态，%s扣除%s', $target->name, $goldName, $amount));
        $paid = $process->receivables($target, $amount, $user->id,
            sprintf('“%s”打赏了你的动态', $user->name),
            sprintf('“%s”打赏了你的动态，%s增加%s', $user->name, $goldName, $amount));
        if ($pay && $paid) {
            // 打赏记录
            $feed->reward($user, $amount);
            $target->notify(new SystemNotification(sprintf('%s打赏了你的动态',
                $user->name), [
                    'type'    => 'reward:feeds',
                    'sender'  => [
                        'id'   => $user->id,
                        'name' => $user->name,
                    ],
                    'amount'  => $amount,
                    'unit'    => $goldName,
                    'feed_id' => $feed->id,
                ]));

            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json(['message' => '打赏成功'], 201);
        } else {
            Cache::forget(sprintf(CacheNames::REWARD_FEED_LOCK, $feed->id,
                $user->id));

            return response()->json(['message' => '打赏失败'], 500);
        }
    }
}
