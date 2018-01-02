<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\PlusGroup\Models\Post as GroupPostModel;
use Zhiyi\PlusGroup\API\Requests\StoreComment as StoreCommentRequest;

class PostCommentController
{
    /**
     * get list of comments.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function get(Request $request, GroupPostModel $post)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $datas = $post->comments()->when($after, function ($query) use($after){
            return $query->where('id', '<', $after);
        })
        ->with(['user', 'reply'])
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'pinneds' => ! $after ? app()->call([$this, 'pinneds'], ['post' => $post]) : collect([]),
            'comments' => $datas,
        ], 200);
    }

    /**
     * get pinned comment of a post.
     *
     * @param Carbon $datetime
     * @param GroupPostModel $post
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function pinneds(Carbon $datetime, GroupPostModel $post)
    {
        return $post->comments()->whereExists(function ($query) use ($post, $datetime) {
            return $query->from('group_pinneds')->whereRaw('group_pinneds.target = comments.id')
                ->where('group_pinneds.raw', $post->id)
                ->where('expires_at', '>', $datetime);
        })->with('user')->get();    
    }

    /**
     * store a comment for a group post.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @param CommentModel $commentModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function store(StoreCommentRequest $request, GroupPostModel $post, CommentModel $commentModel)
    {
        $body = $request->input('body');
        $reply = $request->input('reply_user', 0);
        $user = $request->user();

        $group = $post->group;
        $member = $group->members()->where('user_id', $user->id)->where('audit', 1)->first();
        if ($member && $member->disabled == 1) {
            return response()->json(['message' => ['您已被该圈子拉黑，无法发送评论']], 403);
        }

        if ($group->model != 'public' && (! $member || $member->disabled == 1)) {
            return response()->json(['message' => ['您没有评论权限']], 403);
        }

        $commentModel->user_id = $user->id;
        $commentModel->target_user = $post->user_id;
        $commentModel->reply_user = $reply;
        $commentModel->body = $body;

        $post->getConnection()->transaction(function () use ($post, $commentModel, $reply, $user) {
            $post->comments()->save($commentModel);
            $post->increment('comments_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);

            if ($post->user_id !== $user->id) {
                $post->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                app(Push::class)->push(sprintf('%s评论了你的帖子', $user->name), (string) $post->user->id, ['channel' => 'group:comment']);
            }

            if ($reply && $reply !== $user->id && $reply !== $post->user_id) {
                $replyUser = app(UserModel::class)->find($reply);
                $replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                app(Push::class)->push(sprintf('%s回复了你的评论', $user->name), (string) $replyUser->id, ['channel' => 'group:comment-reply']);
            }

            $commentModel->load(['user', 'reply', 'target']);
        });

        return response()->json([
            'message' => ['操作成功'],
            'comment' => $commentModel,
        ], 201);
    }

    /**
     * delete a comment.
     *
     * @param Request $request
     * @param GroupPostModel $post
     * @param CommentModel $comment
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function delete(Request $request, GroupPostModel $post, CommentModel $comment)
    {
        $user = $request->user();
        if ($user->id !== $comment->user_id && ! $post->group->isManager($user)) {
            return response()->json(['message' => ['没有权限']], 403);
        }

        $post->getConnection()->transaction(function () use ($post, $comment, $user) {
            $post->decrement('comments_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return response()->json()->setStatusCode(204);
    }
}
