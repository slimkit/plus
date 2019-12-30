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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Support\Configuration;

class NewsConfigController extends Controller
{
    /**
     * 查看资讯配置.
     * @return mixed
     */
    public function show()
    {
        $config = setting('news', 'contribute', [
            'pay' => true,
            'verified' => true,
        ]);
        $payAmount = setting('news', 'contribute-amount', 100);

        return response()->json([
            'contribute' => $config,
            'pay_contribute' => $payAmount,
        ], 200);
    }

    /**
     * 更新投稿配置.
     *
     * @param  Request       $request
     * @param  Configuration $configuration
     */
    public function setContribute(Request $request)
    {
        $contribute = $request->input('contribute');
        setting('news')->set('contribute', [
            'pay' => (bool) $contribute['pay'] ?? false,
            'verified' => (bool) $contribute['verified'] ?? false,
        ]);

        return response()->json([], 204);
    }

    /**
     * 投稿金额配置.
     *
     * @param  Request       $request
     * @param  Configuration $configuration
     */
    public function setPayContribute(Request $request)
    {
        $pay_contribute = $request->input('pay_contribute');
        if ($pay_contribute > 9999999 || $pay_contribute < 1) {
            return response()->json(['message' => ['请输入合适的投稿金额']], 422);
        }

        setting('news')->set('contribute-amount', $pay_contribute);

        return response()->json([], 204);
    }
}
