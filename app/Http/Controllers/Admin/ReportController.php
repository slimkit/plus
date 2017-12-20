<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Report as ReportModel;

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
