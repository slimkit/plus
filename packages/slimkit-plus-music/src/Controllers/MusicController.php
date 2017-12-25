<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;

class MusicController extends Controller
{
    public function getMusicInfo(Request $request, int $music_id)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $musicInfo = Music::where('id', $music_id)->with(['singer' => function ($query) {
            $query->with('cover');
        }])->first();
        if (! $musicInfo) {
            return response()->json([
                'status' => false,
                'code' => 8002,
                'message' => '歌曲不存在或已被删除',
            ])->setStatusCode(404);
        }

        $musicInfo->isdiggmusic = $musicInfo->liked($uid) ? 1 : 0;

        Music::where('id', $music_id)->increment('taste_count'); // 歌曲增加播放数量
        MusicSpecial::whereIn('id', MusicSpecialLink::where('music_id', $music_id)->pluck('special_id'))->increment('taste_count'); // 相应专辑增加播放数量

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data' => $musicInfo,
        ])->setStatusCode(200);
    }

    public function share(int $music_id)
    {
        Music::where('id', $music_id)->increment('share_count');
        MusicSpecial::whereIn('id', MusicSpecialLink::where('music_id', $music_id)->pluck('special_id'))->increment('share_count');

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => 'ok',
        ]))->setStatusCode(201);
    }
}
