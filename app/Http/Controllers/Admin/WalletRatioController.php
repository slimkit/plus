<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class WalletRatioController extends Controller
{
    /**
     * 获取充值转换值.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response)
    {
        $ratio = CommonConfig::byNamespace('common')
            ->byName('wallet:ratio')
            ->value('value') ?: 100;

        return $response
            ->json(['ratio' => $ratio])
            ->setStatusCode(200);
    }

    /**
     * 更新转换比例.
     *
     * @param Request $request
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ResponseFactory $response)
    {
        $ratio = intval($request->input('ratio'));

        if ($ratio < 1 || $ratio > 1000) {
            return $response
                ->json(['message' => ['转换比例只能在 1 - 1000 之间']])
                ->setStatusCode(422);
        }

        CommonConfig::updateOrCreate(
            ['namespace' => 'common', 'name' => 'wallet:ratio'],
            ['value' => $ratio]
        );

        return $response
            ->json(['message' => ['更新成功']])
            ->setStatusCode(201);
    }
}
