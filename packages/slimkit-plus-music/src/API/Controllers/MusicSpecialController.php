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

namespace Zhiyi\Plus\Packages\Music\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicSpecialController extends Controller
{
    /**
     * 获取专辑列表.
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
            ->where(function ($query) use ($request) {
                if ($request->max_id > 0) {
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->with(['storage', 'paidNode'])
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
     * 专辑详情.
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

        $special->load(['musics' => function ($query) {
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
