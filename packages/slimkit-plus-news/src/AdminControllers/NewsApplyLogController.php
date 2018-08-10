<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsApplyLog;

class NewsApplyLogController extends Controller
{
    /**
     * 删除申请列表.
     *
     * @param Request $request
     * @param NewsApplyLog $model
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, NewsApplyLog $model)
    {
        $limit = $request->query('limit', 15);
        // $offset = $request->query('offset', 0);
        $key = $request->query('key');
        $user_id = $request->query('user_id');
        $news_id = $request->query('news_id');
        $query = $model->when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })->when($news_id, function ($query) use ($news_id) {
            return $query->where('news_id', $news_id);
        })->whereHas('news', function ($query) use ($key) {
            return $query->when($key, function ($query) use ($key) {
                return $query->where('news.title', 'like', '%'.$key.'%');
            })->withTrashed();
        });
        // $total = $query->count();
        $datas = $query->limit($limit)->with(['news' => function ($query) {
            return $query->withTrashed();
        }, 'user'])->get();

        return response()->json($datas, 200);
    }

    public function accept(NewsApplyLog $log)
    {
        $log->getConnection()->transaction(function () use ($log) {
            $log->load(['news', 'user']);
            $log->news->delete();
            $log->status = 1;
            $log->save();
            $log->user->sendNotifyMessage('news:delete:accept', sprintf('资讯《%s》的删除申请已被通过', $log->news->title), [
                'news' => $log->news,
                'log' => $log,
            ]);
        });

        return response()->json('', 204);
    }

    public function reject(Request $request, NewsApplyLog $log)
    {
        $log->status = 2;
        $log->mark = $request->input('mark');
        $log->save();
        $log->user->sendNotifyMessage('news:delete:reject', sprintf('资讯《%s》的删除申请已被拒绝，拒绝理由为%s', $log->news->title, $log->mark), [
            'news' => $log->news,
            'log' => $log,
        ]);

        return response()->json('', 204);
    }
}
