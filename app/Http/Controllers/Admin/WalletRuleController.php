<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;

class WalletRuleController extends Controller
{
    /**
     * 获取�.
     *
     * 值、提现规则.
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
