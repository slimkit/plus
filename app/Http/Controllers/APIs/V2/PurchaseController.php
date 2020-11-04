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

use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class PurchaseController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this
            ->middleware(VerifyUserPassword::class)
            ->only(['pay', 'payByCurrency']);
    }

    /**
     * 获取付费节点和当前用户的付费状态.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\PaidNode $node
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseContract $response, PaidNodeModel $node)
    {
        $node->paid = $node->paid(
            $request->user()->id
        );

        return $response->json($node)->setStatusCode(200);
    }

    /**
     * 支付节点费用.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param \Zhiyi\Plus\Models\PaidNode $node
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pay(Request $request, ResponseContract $response, CacheContract $cache, WalletChargeModel $charge, PaidNodeModel $node)
    {
        $user = $request->user();
        $user->load('wallet');
        $nodeUser = $node->user;

        if ($node->paid($user->id)) {
            return $response->json([
                'message' => ['已经支付费用不能重复支付'],
            ])->setStatusCode(422);
        } elseif (! $user->wallet || $user->wallet->balance < $node->amount) {
            return $response->json([
                'message' => ['余额不足'],
            ])->setStatusCode(403);
        }

        $user->getConnection()->transaction(function () use ($user, $node, $nodeUser, $charge) {
            // 扣除用户余额
            $user->wallet()->decrement('balance', $node->amount);

            // 插入用户扣除费用订单记录
            $userCharge = clone $charge;
            $userCharge->channel = $nodeUser ? 'user' : 'system';
            $userCharge->account = $node->user_id;
            $userCharge->action = 0;
            $userCharge->amount = $node->amount;
            $userCharge->subject = $node->subject;
            $userCharge->body = $node->body;
            $userCharge->status = 1;
            $user->walletCharges()->save($userCharge);

            // 插入购买用户
            $node->users()->sync($user->id, false);

            // 存在发起人钱包，则插入，否则上述余额扣除后不增加到任何账户。
            if ($nodeUser && $nodeUser->wallet) {
                // 为发起人钱包增加
                $nodeUser->wallet->increment('balance', $node->amount);

                // 添加收款订单
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->action = 1;
                $charge->amount = $node->amount;
                $charge->subject = '被'.$node->subject;
                $charge->body = $charge->subject;
                $charge->status = 1;
                $charge->user_id = $nodeUser->id;
                $charge->save();

                // 被购买通知
                $nodeUser->sendNotifyMessage('paid:'.$node->channel, '被'.$user->name.$node->body, [
                    'charge' => $charge,
                    'user' => $user,
                ]);
            }
            if ($nodeUser) {
                $target->notify(new SystemNotification(sprintf('%s买了你的%s', $user->name, $node->subject), [
                    'type' => 'purchase',
                    'sender' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'amount' => $node->amount,
                    'resource' => [
                        'type' => $node->channel,
                        'raw' => $node->raw,
                    ],
                ]));
            }
        });

        $cacheKey = sprintf('paid:%s,%s', $node->id, $user->id);
        $cache->forget($cacheKey);

        return $response->json([
            'message' => ['付费成功'],
        ])->setStatusCode(201);
    }

    /**
     * 使用积分购买付费节点.
     *
     * @param Request $request
     * @param CacheContract $cache
     * @param PaidNodeModel $node
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function payByCurrency(Request $request, CacheContract $cache, PaidNodeModel $node)
    {
        $user = $request->user();
        $user->load('currency');
        $nodeUser = $node->user;

        if ($node->paid($user->id)) {
            return response()->json([
                'message' => ['已经支付费用不能重复支付'],
            ])->setStatusCode(422);
        } elseif (! $user->currency || $user->currency->sum < $node->amount) {
            return response()->json([
                'message' => ['余额不足'],
            ])->setStatusCode(403);
        }

        $process = new UserProcess();

        $extra = [
            'order_title' => $node->subject,
            'order_body' => $node->body,
            'target_order_title' => '被'.$node->subject,
            'target_order_body' => '被'.$node->body,
        ];

        if ($process->complete($user->id, $node->amount, $nodeUser ? $nodeUser->id : 0, $extra) === true) {
            // 插入购买用户
            $node->users()->sync($user->id, false);
            $cacheKey = sprintf('paid:%s,%s', $node->id, $user->id);
            $cache->forget($cacheKey);

            return response()->json([
                'message' => ['付费成功'],
            ])->setStatusCode(201);
        }

        return response()->json(['message' => ['操作失败']], 500);
    }
}
