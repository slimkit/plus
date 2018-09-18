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

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;

/**
 * 资讯分类管理.
 */
class NewsCateController extends Controller
{
    public function getCateList()
    {
        $cates = NewsCate::orderBy('rank', 'desc')
            ->select()
            ->withCount('news')
            ->get();

        return response()->json($cates)->setStatusCode(200);
    }

    public function addCate(Request $requset)
    {
        if ($requset->name) {
            $cate = new NewsCate();
            $cate->name = $requset->name;
            $cate->rank = $requset->input('rank', 0);
            if ($cate->save()) {
                return response()->json(['message' => ['添加成功']])->setStatusCode(200);
            }
        }
    }

    public function doNewsCate(Request $requset)
    {
        $cate_id = $requset->cate_id ?? 0;
        $cate = NewsCate::find($cate_id);
        if ($cate) {
            $cate->name = $requset->name;
            $cate->rank = $requset->input('rank', 0);
            $cate->save();

            return response()->json(['message' => ['编辑成功']])->setStatusCode(204);
        } else {
            $iscate = NewsCate::where('name', $requset->name)->first();
            if ($iscate) {
                return response()->json(['message' => '分类名称已存在'])->setStatusCode(422);
            }

            $cate = new NewsCate();
            $cate->name = $requset->name;
            $cate->rank = $requset->input('rank', 0);
            $cate->save();

            return response()->json(['message' => ['添加成功']])->setStatusCode(201);
        }
    }

    public function delCate(int $cate_id)
    {
        $cate = NewsCate::find($cate_id);
        $news = News::where('cate_id', $cate_id)->get();
        if ($cate) {
            $cate->delete();
            DB::transaction(function () use ($cate, $news) {
                $cate->delete();
                News::where('cate_id', $cate->id)->delete();
                NewsCollection::whereIn('news_id', $news->pluck('id'))->delete();
            });

            return response()->json(['message' => ['删除成功']])->setStatusCode(204);
        }
    }
}
