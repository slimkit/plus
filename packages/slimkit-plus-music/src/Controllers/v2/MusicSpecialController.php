<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicCollection;


class MusicSpecialController extends Controller
{
    /**
     * 获取专辑列表
     *  
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function list(Request $request, MusicSpecial $specialModel, ResponseFactory $response)
    {
        $uid = $request->user('api')->id ?? 0;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $specials = $specialModel->orderBy('id', 'DESC')
            ->where(function($query) use ($request) {
                if($request->max_id > 0){
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->with(['storage','paidNode'])
            ->take($limit)
            ->get();

        $specials = $specialModel->getConnection()->transaction(function () use ($specials, $uid) {
        	return $specials->map(function ($special) use ($uid) {
        		$special->has_collect = $special->hasCollected($uid);
                $special = $special->formatPaidNode($uid);
        		return $special;
        	});
        });

        return $response->json($specials)->setStatusCode(200);
    }

    /**
     * 专辑详情
     *
     * @author bs<414606094@qq.com>
     * @param  Request         $request 
     * @param  MusicSpecial    $special 
     * @param  ResponseFactory $response 
     * @return mix                    
     */
    public function show(Request $request, MusicSpecial $special, ResponseFactory $response)
    {
        $uid = $request->user('api')->id ?? 0;

        if ($special->paidNode !== null && $special->paidNode->paid($uid) === false) {
            return response()->json([
                'message' => ['请购买专辑'],
                'paid_node' => $special->paidNode->id,
                'amount' => $special->paidNode->amount,
            ])->setStatusCode(403);
        }

        $special->load(['musics' => function($query) {
            $query->with(['singer' => function ($query) {
                    $query->with('cover');
            }])->orderBy('id', 'desc');
        }, 'storage']);

        $special = $special->formatPaidNode($uid);
        $special->has_collect = $special->hasCollected($uid);
        $special->musics->map(function ($music) use ($uid) {
        	$music->has_like = $music->liked($uid);
        	$music = $music->formatStorage($uid);
        });

        return $response->json($special)->setStatusCode(200);
    }

    /**
     * 增加分享数,供移动端分享专辑时调用.
     *
     * @author bs<414606094@qq.com>
     * @param  MusicSpecial $special_id
     * @return mixed
     */
    public function share(MusicSpecial $special)
    {
        $special->increment('share_count');

        return response()->json([])->setStatusCode(204);
    }
}
