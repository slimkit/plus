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

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsRecommend;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith;

/**
 * 资讯推荐管理.
 */
class NewsRecommendController extends Controller
{
    public function getRecommendList()
    {
        $datas = NewsRecommend::orderBy('id', 'desc')
            ->with('cover')
            ->get();

        return response()->json($datas)->setStatusCode(200);
    }

    public function doRecommendInfo(Request $request)
    {
        if (! $request->storage_id) {
            return response()->json([
                'message' => ['没有上传封面图片'],
            ], 422);
        }

        if ($request->rid) {
            $recommend = NewsRecommend::find($request->rid);
            if ($recommend) {
                $recommend->type = $request->type;
                $recommend->cate_id = $request->cate_id ?? 0;
                $recommend->cover = $request->storage_id;
                $recommend->data = $request->data ?? '';
                $recommend->save();
            }
        } else {
            $recommend = new NewsRecommend();
            $recommend->type = $request->type;
            $recommend->cate_id = $request->cate_id ?? 0;
            $recommend->cover = $request->storage_id;
            $recommend->data = $request->data ?? '';
            $recommend->save();
        }
        if ($request->storage_id) {
            $fileWith = FileWith::find($request->storage_id);
            if ($fileWith) {
                $fileWith->channel = 'news:recommend:cover';
                $fileWith->raw = $recommend->id;
                $fileWith->save();
            }
        }

        return response()->json(['message' => ['操作成功']])->setStatusCode(201);
    }

    public function delNewsRecommend(int $rid)
    {
        $recommend = NewsRecommend::find($rid);
        if ($recommend) {
            NewsRecommend::where('id', $rid)->delete();

            return response()->json(['message' => ['删除成功']])->setStatusCode(204);
        }
    }

    public function getNewsRecommend(int $rid)
    {
        $recommend = NewsRecommend::find($rid);

        return response()->json($recommend)->setStatusCode(200);
    }
}
