<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicCollection;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicDigg;

class MusicSpecialController extends Controller
{
    /**
     * 获取专辑列表
     *  
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function getSpecialList(Request $request)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $specials = MusicSpecial::orderBy('id', 'DESC')
            ->where(function($query) use ($request) {
                if($request->max_id > 0){
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->with('storage')
            ->take($limit)
            ->get();
        foreach ($specials as $special) {
            $special->is_collection = MusicCollection::where('special_id', $special->id)->where('user_id', $uid)->get()->isEmpty() ? 0 : 1;
        }
        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '专辑列表获取成功',
                'data' => $specials
            ])->setStatusCode(200);
    }

    /**
     * 获取我的专辑列表
     *  
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function getCollectionSpecialList(Request $request)
    {
        $uid = $request->user()->id;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $specials = MusicSpecial::orderBy('id', 'DESC')
            ->whereIn('id', MusicCollection::where('user_id', $uid)->pluck('special_id'))
            ->where(function($query) use ($request) {
                if($request->max_id > 0){
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->with('storage')
            ->take($limit)
            ->get();
        foreach ($specials as $special) {
            $special->is_collection = MusicCollection::where('special_id', $special->id)->where('user_id', $uid)->get()->isEmpty() ? 0 : 1;
        }
        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '专辑列表获取成功',
                'data' => $specials
            ])->setStatusCode(200);
    }

    /**
     * 获取专辑详情
     * 
     * @author bs<414606094@qq.com>
     * @param  Request $request    [description]
     * @param  [type]  $special_id [description]
     * @return [type]              [description]
     */
    public function getSpecialInfo(Request $request, $special_id)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $specialInfo = MusicSpecial::where('id', $special_id)->with(['musics' => function($query) {
            $query->with(['singer' => function ($query) {
                $query->with('cover');
            }])->orderBy('id', 'desc');
        }])->with('storage')->first();

        $specialInfo->is_collection = MusicCollection::where('special_id', $special_id)->where('user_id', $uid)->get()->isEmpty() ? 0 : 1;

        if (!$specialInfo) {
           return response()->json([
                'status' => false,
                'code' => 8001,
                'message' => '专辑不存在或已被删除'
            ])->setStatusCode(404); 
        }
        foreach ($specialInfo->musics as $music) {
            $music->isdiggmusic = MusicDigg::where(['user_id' => $uid, 'music_id' => $music->id])->first() ? 1 : 0;
        }

        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '获取成功',
                'data' => $specialInfo
        ])->setStatusCode(200);
    }

    public function share(int $special_id)
    {
        MusicSpecial::where('id', $special_id)->increment('share_count');

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => 'ok',
        ]))->setStatusCode(201);
    }
}
