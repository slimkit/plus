<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Http\Controllers\Controller;

class RewardController extends Controller
{
    /**
     * 打赏日期分组统计.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Request $request)
    {
        $type = $request->get('type');
        $start = $request->get('start');
        $scope = $request->get('scope');
        $end = $request->get('end');

        if ($scope) {
            if ($scope == 'today') {
                $start = Carbon::now()->startOfDay()->toDateTimeString();
                $end = Carbon::now()->endOfDay()->toDateTimeString();
            } elseif ($scope == 'week') {
                $start = Carbon::now()->addDay(-7)->startOfDay()->toDateTimeString();
                $end = Carbon::now()->toDateTimeString();
            }
        } else {
            if ($start && $end) {
                $start = Carbon::parse($start)->startOfDay()->toDateTimeString();
                $end = Carbon::parse($end)->endOfDay()->toDateTimeString();
            }
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
        $type = $request->get('type');
        $start = $request->get('start');
        $end = $request->get('end');
        $keyword = $request->get('keyword');
        $perPage = (int) $request->get('perPage', 20);

        $items = Reward::with(['user', 'target'])
            ->when($type, function ($query) use ($type) {
                $query->where('rewardable_type', $type);
            })
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [
                    Carbon::parse($start)->startOfDay()->toDateTimeString(),
                    Carbon::parse($end)->endOfDay()->toDateTimeString(),
                ]);
            })
            ->when($keyword, function ($query) use ($keyword) {
                if (is_numeric($keyword)) {
                    $query->where('user_id', $keyword);
                } else {
                    $query->whereHas('user', function ($qeruy) use ($keyword) {
                        $qeruy->where('name', 'like', $keyword);
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json($items, 200);
    }

    public function excelExport()
    {
    }
}
