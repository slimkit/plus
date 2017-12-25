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

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicCollection;

class MusicCollectionController extends Controller
{
    public function addMusicCollection(Request $request, $special_id)
    {
        $musicSpecial = MusicSpecial::find($special_id);
        if (! $musicSpecial) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(404);
        }
        $collection['user_id'] = $request->user()->id;
        $collection['special_id'] = $special_id;
        if (MusicCollection::where($collection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 8006,
                'status' => false,
                'message' => '已收藏该专辑',
            ]))->setStatusCode(400);
        }

        DB::transaction(function () use ($collection, $special_id) {
            MusicCollection::create($collection);
            MusicSpecial::where('id', $special_id)->increment('collect_count');
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '收藏成功',
        ]))->setStatusCode(201);
    }

    public function delMusicCollection(Request $request, $special_id)
    {
        $musicSpecial = MusicSpecial::find($special_id);
        if (! $musicSpecial) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(404);
        }
        $collection['user_id'] = $request->user()->id;
        $collection['special_id'] = $special_id;
        if (! MusicCollection::where($collection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 8007,
                'status' => false,
                'message' => '未收藏该歌曲',
            ]))->setStatusCode(400);
        }

        DB::transaction(function () use ($collection, $special_id) {
            MusicCollection::where($collection)->delete();
            MusicSpecial::where('id', $special_id)->decrement('collect_count');
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消收藏成功',
        ]))->setStatusCode(204);
    }
}
