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

namespace Zhiyi\Plus\Packages\Music\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Plus\Http\Controllers\Controller;

class MusicController extends Controller
{
    /**
     * List songs.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $songs = Music::when(is_array($request->id), function ($query) use ($request) {
            return $query->whereIn('id', $request->id);
        })
        ->when($request->after, function ($query) use ($request) {
            return $query->where('id', '<', $request->after);
        })
        ->limit($request->limit ?: is_array($request->id) ? count($request->id) : 15)
        ->orderBy('id', 'desc')
        ->get();
        $songs = $songs->map(function ($song) use ($request) {
            $song->has_like = $song->liked($request->user()->id ?? 0);

            return $song->formatStorage($request->user()->id ?? 0);
        });

        return $songs;
    }

    /**
     * 专辑详情.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music
     * @return
     */
    public function show(Request $request, Music $music)
    {
        $uid = $request->user('api')->id ?? 0;
        $music->load(['singer' => function ($query) {
            $query->with('cover');
        }]);

        $music->has_like = $music->liked($uid);
        $music = $music->formatStorage($uid);
        $music->increment('taste_count'); // 歌曲增加播放数量

        $music->musicSpecials->each(function ($musicSpecial) {
            $musicSpecial->increment('taste_count');
        }); // 相应专辑增加播放数量

        return response()->json($music)->setStatusCode(200);
    }

    /**
     * 增加歌曲分享数.
     *
     * @author bs<414606094@qq.com>
     * @param  Music  $music
     * @return mixed
     */
    public function share(Music $music)
    {
        $music->increment('share_count');
        $music->musicSpecials->each(function ($musicSpecial) {
            $musicSpecial->increment('share_count');
        });

        return response()->json([])->setStatusCode(204);
    }
}
