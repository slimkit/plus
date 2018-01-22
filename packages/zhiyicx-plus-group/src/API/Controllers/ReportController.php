<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;

class ReportController
{
    /**
     * 举报一个圈子.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function group(Request $request, GroupModel $group, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reason = $request->input('reason');
        if (strlen($reason) > 191) {
            return response()->json(['message' => ['举报理由超出长度限制']], 422);
        }

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $group->user_id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');
        $reportModel->subject = sprintf('圈子：%s', $group->name);

        $group->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
