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

    public function index()
    {

        $data   = request()->only('idnum', 'bib', 'gender', 'mobile', 'state', 'refundid');
        $name = request()->qeury('name')
        $event  = EventModel::all();

        $query  = Registration::latest('id');

        if (isset(request()->query('name')) && request()->query('name')) {
            $query = $query->where('name', 'like', sprintf('%%%s%%',request()->query('name')));
        }

        foreach($data as $key => $value) {
            if (!is_null($value)) {
                $query = $query->where($key, $value);
            }
        }

        $datas = $query->paginate(20);

        $types  = [
        'normal'=>'正常',
        'draw'=>'抽签',
        'audit'=>'等待审核',
        'auditfail'=>'审核未通过',
        'drawfail'=>'未中签',
        'wait'=>'候补中',
        'waitfail'=>'候补失败'];

        return view("admin.registration.index",compact('datas','types','event'));
    }
}
