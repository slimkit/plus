<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateLink;
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

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $cates,
        ]))->setStatusCode(200);
    }

    public function addCate(Request $requset)
    {
        if ($requset->name) {
            $cate = new NewsCate();
            $cate->name = $requset->name;
            if ($cate->save()) {
                return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '添加成功',
                ]))->setStatusCode(200);
            }
        }
    }

    public function doNewsCate(Request $requset)
    {
        $cate_id = $requset->cate_id ?? 0;
        $cate = NewsCate::find($cate_id);
        if ($cate) {
            $cate->name = $requset->name;
            $cate->save();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '编辑成功',
            ]))->setStatusCode(200);
        } else {
            $iscate = NewsCate::where('name', $requset->name)->first();
            if ($iscate) {
                return response()->json(static::createJsonData([
                    'status'  => false,
                    'message' => '分类名称已存在',
                ]))->setStatusCode(201);
            }

            $cate = new NewsCate();
            $cate->name = $requset->name;
            $cate->save();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '添加成功',
            ]))->setStatusCode(200);
        }
    }

    public function delCate(int $cate_id)
    {
        $cate = NewsCate::find($cate_id);
        if ($cate) {
            $cate->delete();
            $news_ids = NewsCateLink::where('cate_id', $cate_id)->pluck('news_id');
            DB::transaction(function () use ($cate, $news_ids) {
                $cate->delete();
                News::whereIn('id', $news_ids)->delete();
                NewsDigg::whereIn('news_id', $news_ids)->delete();
                NewsCateLink::whereIn('news_id', $news_ids)->delete();
                NewsCollection::whereIn('news_id', $news_ids)->delete();
                NewsComment::whereIn('news_id', $news_ids)->delete();
            });

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '删除成功',
            ]))->setStatusCode(200);
        }
    }
}
