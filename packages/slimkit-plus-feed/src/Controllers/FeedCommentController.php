<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Jobs\PushMessage;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment;

class FeedCommentController extends Controller
{
    /**
     * 查看一条微博的评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $feed_id [description]
     * @return [type]           [description]
     */
    public function getFeedCommentList(Request $request, int $feed_id)
    {
        $limit = $request->get('limit', '15');
        $max_id = intval($request->get('max_id'));
        if (! $feed_id) {
            return response()->json([
                'status' => false,
                'code' => 6003,
                'message' => '动态ID不能为空',
            ])->setStatusCode(400);
        }
        $comments = FeedComment::byFeedId($feed_id)->take($limit)->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })->select(['id', 'created_at', 'comment_content', 'user_id', 'to_user_id', 'reply_to_user_id', 'comment_mark'])->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    /**
     * 对一条动态或评论进行评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     */
    public function addComment(Request $request, $feed_id)
    {
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $feedComment = new FeedComment();
        $feedComment->user_id = $request->user()->id;
        $feedComment->feed_id = $feed_id;
        $feedComment->to_user_id = $feed->user_id;
        $feedComment->reply_to_user_id = $request->reply_to_user_id ?? 0;
        $feedComment->comment_content = $request->comment_content;
        $feedComment->comment_mark = $request->input('comment_mark', ($request->user()->id.Carbon::now()->timestamp) * 1000); //默认uid+毫秒时间戳

        $markmap = ['user_id' => $feedComment->user_id, 'comment_mark' => $feedComment->comment_mark]; // 根据用户及移动端标记进行查重 以防移动端重复调用
        if ($existComment = FeedComment::where($markmap)->first()) {
            return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $existComment->id,
            ]))->setStatusCode(200);
        }

        DB::transaction(function () use ($feedComment, $feed) {
            $feedComment->save();
            Feed::byFeedId($feed->id)->increment('feed_comment_count'); //增加评论数量
        });

        if (($feedComment->reply_to_user_id == 0 && $feedComment->to_user_id != $feedComment->user_id) || ($feedComment->reply_to_user_id > 0 && $feedComment->reply_to_user_id != $feedComment->user_id)) {
            $extras = ['action' => 'comment', 'type' => 'feed', 'uid' => $request->user()->id, 'feed_id' => $feed_id, 'comment_id' => $feedComment->id];
            $alert = '有人评论了你，去看看吧';
            $alias = $request->reply_to_user_id > 0 ?: $feed->user_id;

            dispatch(new PushMessage($alert, (string) $alias, $extras));
        }

        return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $feedComment->id,
            ]))->setStatusCode(201);
    }

    /**
     * 删除一条评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request    [description]
     * @param  int     $comment_id [description]
     * @return [type]              [description]
     */
    public function delComment(Request $request, int $feed_id, int $comment_id)
    {
        $uid = $request->user()->id;
        $comment = FeedComment::find($comment_id);

        if ($comment && $uid == $comment->user_id) {
            DB::transaction(function () use ($comment, $feed_id) {
                $comment->delete();
                $comment->feed()->decrement('feed_comment_count'); // 减少评论数量
            });
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '删除成功',
        ]))->setStatusCode(204);
    }

    /**
     * 我收到的评论.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function myComment(Request $request)
    {
        $user_id = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));
        $comments = FeedComment::where(function ($query) use ($user_id) {
            $query->where('to_user_id', $user_id)->orwhere('reply_to_user_id', $user_id);
        })->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->take($limit)->with(['feed' => function ($query) {
            $query->select(['id', 'created_at', 'user_id', 'feed_content', 'feed_title'])->with(['storages' => function ($query) {
                $query->select(['feed_storage_id']);
            }]);
        }])->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '获取成功',
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    /**
     * 根据评论id获取评论信息.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getComment(Request $request)
    {
        $comment_ids = $request->input('comment_ids');
        is_string($comment_ids) && $comment_ids = explode(',', $comment_ids);

        $comments = FeedComment::whereIn('id', $comment_ids)
        ->with(['feed' => function ($query) {
            $query->select(['id', 'created_at', 'user_id', 'feed_content', 'feed_title'])->with(['storages' => function ($query) {
                $query->select(['feed_storage_id']);
            }]);
        }])->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '获取成功',
            'data' => $comments,
        ]))->setStatusCode(200);
    }
}
