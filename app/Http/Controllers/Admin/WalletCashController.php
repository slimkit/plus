<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCash;
use Zhiyi\Plus\Repository\WalletRatio;
use Zhiyi\Plus\Http\Controllers\Controller;

class WalletCashController extends Controller
{
    /**
     * 获取提现列表.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, WalletRatio $repository)
    {
        $user = $request->query('user');
        $status = $request->query('status');
        $order = $request->query('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $limit = $request->query('limit', 20);

        $query = WalletCash::with('user');

        if ($user) {
            $query->where('user_id', $user);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy('id', $order);
        $paginate = $query->paginate($limit);
        $items = $paginate->items();

        if (empty($items)) {
            return response()
                ->json(['message' => ['没有检索到数据']])
                ->setStatusCode(404);
        }

        return response()
            ->json([
                'last_page' => $paginate->lastPage(),
                'current_page' => $paginate->currentPage(),
                'first_page' => 1,
                'cashes' => $items,
                'ratio' => $repository->get(),
            ])
            ->setStatusCode(200);
    }
}
