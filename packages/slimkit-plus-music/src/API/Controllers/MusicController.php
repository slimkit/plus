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
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;

class MusicController extends Controller
{
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
