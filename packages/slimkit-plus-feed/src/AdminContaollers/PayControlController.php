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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Illuminate\Http\Request;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Http\Controllers\Controller;

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
