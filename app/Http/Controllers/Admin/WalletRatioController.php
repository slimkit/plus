<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Repository\WalletRatio;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class WalletRatioController extends Controller
{
    /**
     * 获取�.
     *
     * 值转换值.
     *
     * @param ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactory $response, WalletRatio $repository)
    {
        return $response
            ->json(['ratio' => $repository->get()])
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
    public function update(Request $request, ResponseFactory $response, WalletRatio $repository)
    {
        $ratio = intval($request->input('ratio'));

        if ($ratio < 1 || $ratio > 1000) {
            return $response
                ->json(['message' => ['转换比例只能在 1 - 1000 之间']])
                ->setStatusCode(422);
        }

        $repository->store($ratio);

        return $response
            ->json(['message' => ['更新成功']])
            ->setStatusCode(201);
    }
}
