<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Plus\Models\Comment as CommentModel;

class ReportController extends Controller
{
    /**
     * 举报一个用户.
     *
     * @param Request $request
     * @param UserModel $user
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function user(Request $request, UserModel $user, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $user->id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');

        $user->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }

    /**
     * 举报一条评论.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function comment(Request $request, CommentModel $comment, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $comment->user_id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');

        $comment->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
