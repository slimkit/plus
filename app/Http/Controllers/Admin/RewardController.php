<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Http\Controllers\Controller;

class RewardController extends Controller
{
    public function statistics(Request $request)
    {
        $type = $request->get('type');
        $start = $request->get('start');
        $end = $request->get('end');

        if ($start && $end) {
            $start = Carbon::parse($start)->startOfDay()->toDateTimeString();
            $end = Carbon::parse($end)->endOfDay()->toDateTimeString();
        }

        $items = Reward::select(DB::raw(
            'count(*) AS reward_count, 
             sum(amount) AS reward_amount, 
             LEFT (created_at, 10) AS reward_date'
        ))
        ->when($type, function ($query) use ($type) {
            $query->where('rewardable_type', $type);
        })
        ->when($start && $end, function ($qeury) use ($start, $end) {
            $qeury->whereBetween('created_at', [$start, $end]);
        })
        ->groupBy('reward_date')
        ->get();

        return response()->json($items, 200);
    }

    /**
     * 打赏清单.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rewards(Request $request)
    {
        $items = Reward::with(['user', 'target'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($items, 200);
    }
}
