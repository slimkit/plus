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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\setting;

class PayControlController extends Controller
{
    // 获取当前付费配置
    public function getCurrentStatus()
    {
        return response()->json([
            'open' => setting('feed', 'pay-switch', false),
            'payItems' => implode(',', setting('feed', 'pay-items', [])),
            'textLength' => setting('feed', 'pay-word-limit', 50),
        ])
        ->setStatusCode(200);
    }

    /**
     * 更新动态付费状态
     */
    public function updateStatus(Request $request)
    {
        $paySwitcg = $request->input('open');
        $payWordLimit = intval($request->input('textLength'));
        $paidItems = $request->input('payItems');

        if (is_bool($paySwitcg)) {
            setting('feed')->set('pay-switch', $paySwitcg);
        }

        if ($payWordLimit) {
            setting('feed')->set('pay-word-limit', $payWordLimit);
        }

        if ($paidItems) {
            $paidItems = array_filter(
                explode(',', $paidItems)
            );
            setting('feed')->set('pay-items', $paidItems);
        }

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }
}
