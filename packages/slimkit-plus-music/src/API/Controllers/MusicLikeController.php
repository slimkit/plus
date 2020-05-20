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

class MusicLikeController extends Controller
{
    /**
     * 点赞一个音乐.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  Music   $Music_id [description]
     * @return [type]           [description]
     */
    public function like(Request $request, Music $music)
    {
        $user = $request->user();
        if ($music->liked($user)) {
            return response()->json([
                'message' => ['已赞过该歌曲'],
            ])->setStatusCode(422);
        }
        $music->like($user);

        return response()->json([
            'message' => ['点赞成功'],
        ])->setStatusCode(201);
    }

    /**
     * 取消点赞一个动态
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $Music
     * @return [type]
     */
    public function cancel(Request $request, Music $music)
    {
        $user = $request->user();
        if (! $music->liked($user)) {
            return response()->json([
                'message' => ['未对该歌曲点赞'],
            ])->setStatusCode(400);
        }

        $music->unlike($user);

        return response()->json()->setStatusCode(204);
    }
}
