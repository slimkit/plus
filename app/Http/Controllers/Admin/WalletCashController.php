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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletCash;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use function Zhiyi\Plus\setting;

class WalletCashController extends Controller
{
    /**
     * 获取提现列表.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request)
    {
        $user = $request->query('user');
        $status = $request->query('status');
        $order = $request->query('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $limit = $request->query('limit', 20);

        $query = WalletCash::with('user');

        if ($user) {
            $query->where('user_id', $user);
        }

        if ($status !== null && $status !== 'all') {
            $query->where('status', $status);
        }

        $query->orderBy('id', $order);
        $paginate = $query->paginate($limit);
        $items = $paginate->items();

        if (empty($items)) {
            return response()
                ->json(['message' => ['没有检索到数据']])
                ->setStatusCode(404);
        }

        return response()
            ->json([
                'last_page' => $paginate->lastPage(),
                'current_page' => $paginate->currentPage(),
                'first_page' => 1,
                'cashes' => $items,
                'ratio' => setting('wallet', 'ratio', 100),
            ])
            ->setStatusCode(200);
    }

    /**
     * 通过审批.
     *
     * @param Request $request
     * @param WalletCash $cash
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function passed(Request $request, WalletCash $cash)
    {
        $remark = $request->input('remark');

        if (! $remark) {
            return response()
                ->json(['remark' => ['请输入备注信息']])
                ->setStatusCode(422);
        }

        $user = $request->user();
        $cash->status = 1;
        $cash->remark = $remark;

        // Charge
        $charge = new WalletCharge();
        $charge->amount = $cash->value;
        $charge->channel = $cash->type;
        $charge->action = 0;
        $charge->subject = '账户提现';
        $charge->body = $remark;
        $charge->account = $cash->account;
        $charge->status = 1;
        $charge->user_id = $user->id;

        DB::transaction(function () use ($cash, $charge) {
            $charge->save();
            $cash->save();
        });

        $cash->user->notify(new SystemNotification('你的提现申请已通过', [
            'type' => 'user-cash',
            'state' => 'passed',
        ]));

        return response()
            ->json(['message' => ['操作成功']])
            ->setStatusCode(201);
    }

    /**
     * 提现驳回.
     *
     * @param Request $request
     * @param WalletCash $cash
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refuse(Request $request, WalletCash $cash)
    {
        $remark = $request->input('remark');

        if (! $remark) {
            return response()
                ->json(['remark' => ['请输入备注信息']])
                ->setStatusCode(422);
        }

        $user = $request->user();
        $cash->status = 2;
        $cash->remark = $remark;

        // Charge
        $charge = new WalletCharge();
        $charge->amount = $cash->value;
        $charge->channel = $cash->type;
        $charge->action = 0;
        $charge->subject = '账户提现';
        $charge->body = $remark;
        $charge->account = $cash->account;
        $charge->status = 2;
        $charge->user_id = $user->id;

        DB::transaction(function () use ($cash, $charge) {
            $cash->user->newWallet()->increment('balance', $cash->value);
            $charge->save();
            $cash->save();
        });

        $cash->user->notify(new SystemNotification('你的提现申请已通过', [
            'type' => 'user-cash',
            'state' => 'rejected',
            'contents' => $remark,
        ]));

        return response()
            ->json(['message' => ['操作成功']])
            ->setStatusCode(201);
    }
}
