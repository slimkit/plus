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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsApplyLog;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Notifications\System;

class NewsApplyLogController extends Controller
{
    /**
     * 删除申请列表.
     *
     * @param Request $request
     * @param NewsApplyLog $model
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, NewsApplyLog $model)
    {
        $limit = $request->query('limit', config('app.data_limit'));
        $key = $request->query('key');
        $user_id = $request->query('user_id');
        $news_id = $request->query('news_id');
        $query = $model->newQuery()
            ->when($user_id, function (Builder $query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when($news_id, function (Builder $query) use ($news_id) {
                return $query->where('news_id', $news_id);
            })
            ->whereHas('news', function (Builder $query) use ($key) {
                return $query->when($key, function (Builder $query) use ($key) {
                    return $query->where('news.title', 'like', '%'.$key.'%');
                })
                    ->withTrashed();
            });
        $data = $query->with([
            'news' => function (HasOne $query) {
                return $query->withTrashed();
            }, 'user',
        ])->paginate($limit);

        return response()->json($data, 200);
    }

    public function accept(int $log)
    {
        DB::transaction(function () use ($log) {
            $log = NewsApplyLog::query()->with(['news', 'user'])->find($log);
            $log->news->delete();
            $log->status = 1;
            $log->save();
            $log->user->notify(new System(sprintf('资讯《%s》的删除申请已被通过',
                $log->news->title), [
                    'type' => 'news:delete:accept',
                    'news' => [
                        'id' => $log->news_id,
                        'title' => $log->news->title,
                    ],
                ]));
        });

        return response()->json('', 204);
    }

    public function reject(Request $request, int $log)
    {
        $log = NewsApplyLog::query()->with(['news', 'user'])->find($log);
        $log->status = 2;
        $log->mark = $request->input('mark');
        $log->save();
        // $log->user->sendNotifyMessage('news:delete:reject',
        //     sprintf('资讯《%s》的删除申请已被拒绝，拒绝理由为%s', $log->news->title, $log->mark), [
        //         'news' => $log->news,
        //         'log'  => $log,
        //     ]);
        $log->user->notify(new System(sprintf('资讯《%s》的删除申请已被拒绝，拒绝理由为%s', $log->news->title, $log->mark), [
            'type' => 'news:delete:reject',
            'news' => [
                'id' => $log->news_id,
                'title' => $log->news->title,
            ],
        ]));

        return response()->json('', 204);
    }
}
