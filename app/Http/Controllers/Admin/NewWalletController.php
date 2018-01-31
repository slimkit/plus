<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletOrder;
use Zhiyi\Plus\Http\Controllers\Controller;

class NewWalletController extends Controller
{
    /**
     * 新版钱包统计.
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function statistics()
	{
        $expenditure = WalletOrder::where('type', -1)->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();
        $income = WalletOrder::where('type', 1)->select(DB::raw('count(id) as count, sum(amount) as sum'))->first();

        return response()->json([
            'expenditure' => $expenditure,
            'income' => $income,
        ], 200);
	}

    /**
     * 新版钱包流水.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function waters(Request $request)
	{
	    $user = $request->query('user');
	    $state = $request->query('state');

		$limit = (int) $request->query('limit', 15);
		$offset = (int) $request->query('offset', 0);

		$query = (new WalletOrder)->newQuery();

		$query->when($user, function ($query) use ($user) {
		    return $query->where('owner_id', $user);
        })
        ->when($state, function ($query) use ($state) {
            return $query->where('state', $state);
        });

		$count = $query->count();
		$items = $query->limit($limit)->offset($offset)->latest()->get();

		return response()->json($items, 200, ['x-total' => $count]);
	}    
}
