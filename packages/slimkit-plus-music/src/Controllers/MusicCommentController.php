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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Jobs\PushMessage;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;

class MusicCommentController extends Controller
{
    /**
     * 查看一条歌曲的评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $music_id [description]
     * @return [type]           [description]
     */
    public function getMusicCommentList(Request $request, int $music_id)
    {
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));
        $comments = MusicComment::where('music_id', $music_id)->take($limit)->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    /**
     * 查看一个专辑的评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request    [description]
     * @param  int     $special_id [description]
     * @return [type]              [description]
     */
    public function getSpecialCommentList(Request $request, int $special_id)
    {
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));
        $comments = MusicComment::where(function ($query) use ($special_id) {
            $query->where('special_id', $special_id)->orwhere(function ($query) use ($special_id) {
                $query->whereIn('music_id', MusicSpecialLink::where('special_id', $special_id)->pluck('music_id'));
            });
        })->take($limit)->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    /**
     * 对一条音乐或评论进行评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     */
    public function addComment(Request $request, $music_id)
    {
        $music = Music::with('speciallinks')->find($music_id);
        if (! $music) {
            return response()->json(static::createJsonData([
                'code' => 8002,
            ]))->setStatusCode(403);
        }
        $MusicComment = new MusicComment();
        $MusicComment->user_id = $request->user()->id;
        $MusicComment->music_id = $music_id;
        $MusicComment->reply_to_user_id = $request->reply_to_user_id ?? 0;
        $MusicComment->comment_content = $request->comment_content;
        $MusicComment->comment_mark = $request->input('comment_mark', ($request->user()->id.Carbon::now()->timestamp) * 1000); //默认uid+毫秒时间戳

        DB::transaction(function () use ($MusicComment, $music) {
            $MusicComment->save();
            Music::where('id', $music->id)->increment('comment_count'); //增加评论数量
            MusicSpecial::whereIn('id', $music->speciallinks->pluck('special_id'))->increment('comment_count'); //增加评论数量
        });

        if ($MusicComment->reply_to_user_id > 0 && $MusicComment->reply_to_user_id != $request->user()->id) {
            $extras = ['action' => 'comment', 'type' => 'music', 'uid' => $request->user()->id, 'music_id' => $music_id, 'comment_id' => $MusicComment->id];
            $alert = '有人评论了你，去看看吧';
            $alias = $MusicComment->reply_to_user_id;

            dispatch(new PushMessage($alert, (string) $alias, $extras));
        }

        return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $MusicComment->id,
            ]))->setStatusCode(201);
    }

    public function addSpecialComment(Request $request, $special_id)
    {
        $special = MusicSpecial::find($special_id);
        if (! $special) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(403);
        }
        $MusicComment = new MusicComment();
        $MusicComment->user_id = $request->user()->id;
        $MusicComment->special_id = $special_id;
        $MusicComment->reply_to_user_id = $request->reply_to_user_id ?? 0;
        $MusicComment->comment_content = $request->comment_content;
        $MusicComment->comment_mark = $request->input('comment_mark', ($request->user()->id.Carbon::now()->timestamp) * 1000); //默认uid+毫秒时间戳

        DB::transaction(function () use ($MusicComment, $special_id) {
            $MusicComment->save();
            MusicSpecial::where('id', $special_id)->increment('comment_count'); //增加评论数量
        });

        if ($MusicComment->reply_to_user_id > 0 && $MusicComment->reply_to_user_id != $request->user()->id) {
            $extras = ['action' => 'comment', 'type' => 'music', 'uid' => $request->user()->id, 'special_id' => $special_id, 'comment_id' => $MusicComment->id];
            $alert = '有人评论了你，去看看吧';
            $alias = $MusicComment->reply_to_user_id;

            dispatch(new PushMessage($alert, (string) $alias, $extras));
        }

        return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $MusicComment->id,
            ]))->setStatusCode(201);
    }

    public function delComment(Request $request, int $comment_id)
    {
        $uid = $request->user()->id;
        $comment = MusicComment::find($comment_id);

        if ($comment && $uid == $comment->user_id) {
            DB::transaction(function () use ($comment) {
                $comment->delete();
                if ($comment->music_id > 0) {
                    Music::where('id', $comment->music_id)->decrement('comment_count');
                    MusicSpecial::whereIn('id', MusicSpecialLink::where('music_id', $comment->music_id)->pluck('special_id'))->decrement('comment_count');
                } elseif ($comment->special_id > 0) {
                    MusicSpecial::where('id', $comment->special_id)->decrement('comment_count');
                }
            });
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '删除成功',
        ]))->setStatusCode(204);
    }
}
