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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Packages\Wallet\Order;
use Illuminate\Database\Eloquent\Builder;
use Zhiyi\Plus\Packages\Wallet\TypeManager;
use Zhiyi\Plus\Http\Requests\API2\NewStoreUserWallerCashPost;

class NewWalletCashController extends Controller
{
    /**
     * 获取提现列表.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
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
     * @param \Zhiyi\Plus\Http\Requests\API2\NewStoreUserWallerCashPost $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(NewStoreUserWallerCashPost $request, TypeManager $manager)
    {
        $value = (int) $request->input('value');
        $type = $request->input('type');
        $account = $request->input('account');
        $user = $request->user();

        if ($manager->driver(Order::TARGET_TYPE_WITHDRAW)->widthdraw($user, $value, $type, $account) === true) {
            return response()
                ->json(['message' => ['提交申请成功']])
                ->setStatusCode(201);
        }

        return response()->json(['message' => '操作失败'], 500);
    }
}
