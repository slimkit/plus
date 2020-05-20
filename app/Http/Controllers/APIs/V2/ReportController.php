<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Plus\Models\User as UserModel;

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
        $reportModel->subject = sprintf('用户：%s', $user->name);

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
        $reportModel->subject = sprintf('评论：%s', mb_substr($comment->body, 0, 50));

        $comment->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
