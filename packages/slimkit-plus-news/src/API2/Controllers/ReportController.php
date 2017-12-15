<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;

class ReportController extends Controller
{
    /**
     * 举报一条资讯.
     *
     * @param Request $request
     * @param NewsModel $news
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function news(Request $request, NewsModel $news, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $news->user_id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');

        $news->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
