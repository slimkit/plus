<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class PurchaseController extends Controller
{
    /**
     * 获取付费节点和当前用户的付费状态.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\PaidNode $node
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseContract $response, PaidNodeModel $node)
    {
        $node->paid = $node->paid(
            $request->user()->id
        );
        return $response->json($node)->setStatusCode(200);
    }
}
