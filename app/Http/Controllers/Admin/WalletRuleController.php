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
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class WalletRuleController extends Controller
{
    /**
     * Get the recharge and withdraw the rules.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response)
    {
        $rule = CommonConfig::byNamespace('wallet')
            ->byName('rule')
            ->value('value');

        return $response
            ->json(['rule' => $rule])
            ->setStatusCode(200);
    }

    /**
     * 更新规则.
     *
     * @param Request $request
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ResponseFactory $response)
    {
        $rule = $request->input('rule', '');

        CommonConfig::updateOrCreate(
            ['name' => 'rule', 'namespace' => 'wallet'],
            ['value' => $rule]
        );

        return $response
            ->json(['message' => ['更新成功']])
            ->setStatusCode(201);
    }
}
