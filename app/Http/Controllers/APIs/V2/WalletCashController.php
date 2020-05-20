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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Requests\API2\StoreUserWallerCashPost;
use Zhiyi\Plus\Models\WalletCash;

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
        $user = $request->user();
        $after = $request->query('after');
        $limit = $request->query('limit', 15);

        $query = $user->walletCashes();
        $query->where(function (Builder $query) use ($after) {
            if ($after) {
                $query->where('id', '<', $after);
            }
        });
        $query->limit($limit);
        $query->orderBy('id', 'desc');

        return response()
            ->json($query->get(['id', 'value', 'type', 'account', 'status', 'remark', 'created_at']))
            ->setStatusCode(200);
    }

    /**
     * 提交提现申请.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreUserWallerCashPost $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUserWallerCashPost $request)
    {
        $value = $request->input('value');
        $type = $request->input('type');
        $account = $request->input('account');
        $user = $request->user();

        // Create Cash.
        $cash = new WalletCash();
        $cash->value = $value;
        $cash->type = $type;
        $cash->account = $account;
        $cash->status = 0;

        DB::transaction(function () use ($user, $value, $cash) {
            $user->wallet()->decrement('balance', $value);
            $user->walletCashes()->save($cash);
        });

        return response()
            ->json(['message' => ['提交申请成功']])
            ->setStatusCode(201);
    }
}
