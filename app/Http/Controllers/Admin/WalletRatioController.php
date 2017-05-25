<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

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
}