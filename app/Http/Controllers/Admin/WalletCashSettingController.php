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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Repository\UserWalletCashType;
use Zhiyi\Plus\Repository\WalletCashMinAmount;

class WalletCashSettingController extends Controller
{
    /**
     * 获取提现设置.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(UserWalletCashType $typeRepository, WalletCashMinAmount $minAmountRepository)
    {
        return response()->json([
            'types' => $typeRepository->get(),
            'min_amount' => $minAmountRepository->get(),
        ])->setStatusCode(200);
    }

    /**
     * 更新提现设置.
     *
     * @param Request $request
     * @return mexed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, UserWalletCashType $typeRepository, WalletCashMinAmount $minAmountRepository)
    {
        $rules = [
            'types' => 'array|in:alipay,wechat',
            'min_amount' => 'required|numeric|min:1',
        ];
        $messages = [
            'types.array' => '提交的数据有误，请刷新重试',
            'types.in_array' => '提交的数据不合法，请刷新重试',
            'min_amount.required' => '请输入最低提现金额',
            'min_amount.numeric' => '最低金额必须为数字',
            'min_amount.min' => '最低提现金额出错',
        ];

        $this->validate($request, $rules, $messages);
        $typeRepository->store(
            $request->input('types', [])
        );
        $minAmountRepository->store(
            intval($request->input('min_amount', 1))
        );

        return response()
            ->json(['messages' => ['更新成功']])
            ->setStatusCode(201);
    }
}
