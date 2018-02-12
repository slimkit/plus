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
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
        if (mb_strlen($reason) > 255) {
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
