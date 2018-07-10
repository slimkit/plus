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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class RankController extends Controller
{
    /**
     * 获取资讯排行.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News $newsModel
     * @param  Carbon\Carbon  $datetime
     * @return  mixed
     */
    public function index(Request $request, News $newsModel, Carbon $datetime)
    {
        $user = $request->user('api')->id ?? 0;
        $type = $request->query('type', 'day');
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        switch ($type) {
            case 'day':
                $date = $datetime->subDay();
                break;
            case 'week':
                $date = $datetime->subWeek();
                break;
            case 'month':
                $date = $datetime->subMonth();
                break;
            default:
                $date = $datetime->subDay();
                break;
        }

        $news = $newsModel->select('user_id', DB::raw('sum(hits) as count'))
        ->where('created_at', '>', $date)
        ->where('audit_status', 0)
        ->with(['user' => function ($query) {
            return $query->select('id', 'name', 'sex')->withTrashed();
        }])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->orderBy('user_id', 'asc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return response()->json($newsModel->getConnection()->transaction(function () use ($news, $user, $date, $newsModel, $offset) {
            return $news->map(function ($new, $key) use ($user, $offset) {
                $new->user->following = $new->user->hasFollwing($user);
                $new->user->follower = $new->user->hasFollower($user);

                $return = $new->user->toArray();
                $return['extra']['rank'] = $key + $offset + 1;
                $return['extra']['count'] = (int) $new->count;

                return $return;
            });
        }), 200);
    }
}
