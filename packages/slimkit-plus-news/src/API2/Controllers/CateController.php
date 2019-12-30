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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateFollow;
use Zhiyi\Plus\Http\Controllers\Controller;

class CateController extends Controller
{
    /**
     * 分类列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function list(Request $request)
    {
        $user_id = $request->user('api')->id ?? 0;

        // 所有分类
        $cates = NewsCate::orderBy('rank', 'desc')->select('id', 'name')->get();

        // 我订阅的分类
        $follows = NewsCateFollow::where('user_id', $user_id)->first();
        $follows_array = [];
        // 第一次使用，默认返回5个
        if (! $follows) {
            $follows_array = NewsCate::orderBy('rank', 'desc')->take(5)->pluck('id')->toArray();
        }
        if ($follows && $follows->follows) {
            $follows_array = explode(',', $follows->follows);
        }
        // 更多分类
        $datas = ['my_cates' => [], 'more_cates' => []];

        $cates->each(function ($cate) use (&$datas, $follows_array) {
            in_array($cate['id'], $follows_array) ? $datas['my_cates'][] = $cate : $datas['more_cates'][] = $cate;
        });

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * Follow news cate.
     * @param  $follows [分类字符串]
     * @return mixed 返回结果
     */
    public function follow(Request $request, NewsCateFollow $followModel)
    {
        $uid = $request->user()->id;
        $follows = $request->input('follows', '');

        $followModel->updateOrCreate(['user_id' => $uid], ['follows' => $follows]);

        return response()->json([
            'message' => ['订阅成功'],
        ])->setStatusCode(201);
    }
}
