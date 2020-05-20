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
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\setting;

class WalletCashSettingController extends Controller
{
    /**
     * 获取提现设置.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show()
    {
        return response()->json([
            'types' => setting('wallet', 'cash-types', []),
            'min_amount' => setting('wallet', 'cash-min-amount', 100),
        ])->setStatusCode(200);
    }

    /**
     * 更新提现设置.
     *
     * @param Request $request
     * @return mexed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request)
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
        setting('wallet')->set([
            'cash-types' => $request->input('types', []),
            'cash-min-amount' => intval($request->input('min_amount', 1)),
        ]);

        return response()
            ->json(['messages' => ['更新成功']])
            ->setStatusCode(201);
    }
}
