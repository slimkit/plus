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
        $startDate = $request->get('start');
        $endDate = $request->get('end');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
            $end = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
        } else {
            $start = Carbon::now()->startOfDay()->toDateTimeString();
            $end = Carbon::now()->endOfDay()->toDateTimeString();
        }

        $items = Reward::select(DB::raw(
            'count(*) AS reward_count, 
             sum(amount) AS reward_amount, 
             LEFT (created_at, 10) AS reward_date'
        ))
        ->when($type, function ($query) use ($type) {
            $query->where('rewardable_type', $type);
        })
          ->whereBetween('created_at', [$start, $end])
        ->groupBy('reward_date')
        ->get();

        return response()->json($items, 200);
    }
}
