<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class ReportController extends Controller
{
    /**
     * 举报一条动态.
     *
     * @param Request $request
     * @param FeedModel $feed
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function feed(Request $request, FeedModel $feed, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $feed->user_id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');

        $feed->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
