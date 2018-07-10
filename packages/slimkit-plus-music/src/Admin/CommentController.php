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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Comment as MusicComment;

class CommentController extends Controller
{
    /**
     * 所有歌曲应用相关评论.
     * @param  Request      $request [description]
     * @param  MusicComment $comment [description]
     * @return [type]                [description]
     */
    public function lists(Request $request, MusicComment $comment)
    {
        $keyword = $request->query('keyword', '');
        $limit = $request->query('limit', 20);
        $lists = $comment->when($keyword, function ($query) use ($keyword) {
            return $query->where('body', 'like', "%{$keyword}%");
        })
            ->where(function ($query) {
                $query->where('commentable_type', 'musics')
                    ->orWhere('commentable_type', 'music_specials');
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);

        $comments = $lists->map(function ($list) {
            if ($list->commentable_type === 'musics') {
                $list->load('music');
            }
            if ($list->commentable_type === 'music_specials') {
                $list->load('special');
            }

            return $list;
        });

        return view('allComments', [
            'comments' => collect($comments),
            'page' => $lists->links(),
            'type' => 'music',
            'keyword' => $keyword,
        ]);
    }

    /**
     * 歌曲评论.
     * @param  Request $request [description]
     * @param  Music   $music   [description]
     * @return [type]           [description]
     */
    public function musicComments(Request $request, Music $music)
    {
        $keyword = $request->query('keyword', '');
        $limit = $request->query('limit', 20);

        $limit = $request->input('limit', 15);
        $comments = $music->comments()
            ->when(
                $keyword,
                function ($query) use ($keyword) {
                    return $query->where('body', 'like', "%{$keyword}%");
                }
            )
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return view('comments', [
            'comments' => $comments->items(),
            'page' => $comments->links(),
            'keyword' => $keyword,
            'type' => 'music',
            'music' => $music,
        ]);
    }

    /**
     * 专辑评论.
     * @param  Request      $request [description]
     * @param  MusicSpecial $special [description]
     * @return [type]                [description]
     */
    public function specialComments(Request $request, MusicSpecial $special)
    {
        $limit = $request->query('limit', 20);
        $comments = $special->comments()
        ->orWhere(
            function ($query) use ($special) {
                return $query->where('commentable_type', 'musics')->whereIn('commentable_id', $special->musics->pluck('id'));
            }
        )
        ->orderBy('id', 'desc')
        ->paginate($limit);

        return view('comments', [
            'comments' => $comments->items(),
            'page' => $comments->links(),
            'type' => 'special',
            'special' => $special,
        ]);
    }

    /**
     * 删除音乐评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music
     * @param  Comment $comment
     * @return json
     */
    public function delete(Music $music, Comment $comment)
    {
        $comment->load('user');

        $music->getConnection()->transaction(function () use ($music, $comment) {
            $music->decrement('comment_count', 1);
            $music->musicSpecials()->decrement('comment_count', 1);
            $comment->user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return back()->with(['success-message' => '删除成功']);
    }

    /**
     * 删除专辑评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @param  Comment      $comment
     * @return json
     */
    public function specialDelete(MusicSpecial $special, Comment $comment)
    {
        $comment->load('user');

        $special->getConnection()->transaction(function () use ($special, $comment) {
            $special->decrement('comment_count', 1);
            $comment->user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return back()->with(['success-message' => '删除成功']);
    }
}
