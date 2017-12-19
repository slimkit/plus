<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Plus\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * 举报列表.
     * 
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        $limit = $request->query('limit');
        $offset = $request->query('offset');

        $query = ReportModel::with(['user', 'target', 'reportable']);

        $count = $query->count();
        $items = $query->limit($limit)->offset($offset)->get();

        return response()->json($items, 200, ['x-total' => $count]);
    }

    /**
     * 举报审核.
     * 
     * @param  Request     $request
     * @param  ReportModel $report 
     * @return mixed        
     */
    public function audit(Request $request, ReportModel $report)
    {
        $status = (int) $request->input('status');
        dd(1);
        // $report->status = $status;
        // $report->mark = $request->input('mark');
        // $reprot->save();
    }


}
