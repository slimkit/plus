<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsRecommend;

/**
 * 资讯推荐管理.
 */
class NewsRecommendController extends Controller
{
    public function getRecommendList(Request $request)
    {
        $datas = NewsRecommend::orderBy('id', 'desc')
            ->with('cover')
            ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    public function doRecommendInfo(Request $request)
    {
        if (! $request->storage_id) {
            return response()->json([
                'status' => false,
                'message' => '没有上传封面图片',
            ]);
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

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '操作成功',
        ]))->setStatusCode(200);
    }

    public function delNewsRecommend(int $rid)
    {
        $recommend = NewsRecommend::find($rid);
        if ($recommend) {
            NewsRecommend::where('id', $rid)->delete();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '删除成功',
            ]))->setStatusCode(200);
        }
    }

    public function getNewsRecommend(int $rid)
    {
        $recommend = NewsRecommend::find($rid);

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data' => $recommend,
        ]))->setStatusCode(200);
    }
}
