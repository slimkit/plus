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

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use SlimKit\PlusQuestion\API2\Requests\CommentRequest;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class CommentController extends Controller
{
    /**
     * 问题评论列表.
     */
    public function questionComments(Request $request, QuestionModel $question, ResponseFactoryContract $response)
    {
        $after = $request->input('after');
        $limit = $request->input('limit', 15);
        $comments = $question->comments()
            ->when($after, function ($query) use ($after) {
                $query->where('id', '<', $after);
            })
        ->with(['user', 'reply'])
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->get();

        return $response->json($comments)
            ->setStatusCode(200);
    }

    /**
     * 问题回答评论.
     */
    public function answerComments(Request $request, AnswerModel $answer, ResponseFactoryContract $response)
    {
        $after = $request->input('after');
        $limit = $request->input('limit', 15);
        $comments = $answer->comments()
            ->when($after, function ($query) use ($after) {
                $query->where('id', '<', $after);
            })
        ->with(['user', 'reply'])
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->get();

        return $response->json($comments)
            ->setStatusCode(200);
    }

    /**
     * 存储问题评论.
     */
    public function storeQuestionComment(CommentRequest $request, QuestionModel $question, Comment $comment)
    {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();
        $mark = $request->input('comment_mark', '');

        $comment->user_id = $user->id;
        $comment->target_user = $question->user_id;
        $comment->reply_user = $replyUser;
        $comment->body = $body;
        $comment->comment_mark = $mark;

        $question->getConnection()->transaction(function () use ($question, $comment, $user) {
            $question->comments()->save($comment);
            $question->increment('comments_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);
            if ($question->user_id !== $user->id) {
                $question->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                // 1.8启用, 新版未读消息提醒
                $userCount = UserCountModel::firstOrNew([
                    'type' => 'user-commented',
                    'user_id' => $question->user_id
                ]);
                $userCount->total += 1;
                $userCount->save();

                app(Push::class)->push(sprintf('%s评论了你的问题', $user->name), (string) $question->user->id, ['channel' => 'question:comment']);
            }
        });

        if ($replyUser && $replyUser !== $user->id && $replyUser !== $question->user_id) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
            // 1.8启用, 新版未读消息提醒
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-commented',
                'user_id' => $replyUser->id
            ]);
            $userCount->total += 1;
            $userCount->save();

            app(Push::class)->push(sprintf('%s回复了您的评论', $user->name), (string) $replyUser->id, ['channel' => 'question:comment-reply']);
        }
        $comment->load('user', 'target', 'reply');
        return response()->json([
            'message' => ['操作成功'],
            'comment' => $comment,
        ])->setStatusCode(201);
    }

    /**
     * 存储回答评论.
     */
    public function storeAnswerComment(CommentRequest $request, AnswerModel $answer, Comment $comment)
    {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();
        $mark = $request->input('comment_mark', '');

        $comment->user_id = $user->id;
        $comment->target_user = $answer->user_id;
        $comment->reply_user = $replyUser;
        $comment->body = $body;
        $comment->comment_mark = $mark;

        $answer->getConnection()->transaction(function () use ($comment, $user, $answer) {
            $answer->comments()->save($comment);
            $answer->increment('comments_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);
            if ($answer->user_id !== $user->id) {
                $answer->user->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
                // 1.8启用, 新版未读消息提醒
                $userCount = UserCountModel::firstOrNew([
                    'type' => 'user-commented',
                    'user_id' => $answer->user_id
                ]);
                $userCount->total += 1;
                $userCount->save();
                app(Push::class)->push(sprintf('%s评论了你的回答', $user->name), (string) $answer->user->id, ['channel' => 'answer:comment']);
            }
        });

        // 通知被回复的用户
        if ($replyUser && $replyUser !== $user->id && $replyUser !== $answer->user_id) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $replyUser->unreadCount()->firstOrCreate([])->increment('unread_comments_count', 1);
            // 1.8启用, 新版未读消息提醒
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-commented',
                'user_id' => $replyUser->id
            ]);
            $userCount->total += 1;
            $userCount->save();
            app(Push::class)->push(sprintf('%s回复了您的评论', $user->name), (string) $replyUser->id, ['channel' => 'answer:comment-reply']);
        }
        $comment->load('user', 'target', 'reply');
        return response()->json([
            'message' => ['操作成功'],
            'comment' => $comment,
        ])->setStatusCode(201);
    }

    /**
     * delete a comment of a question.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Question $question
     * @param  Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     */
    public function delQuestionComment(Request $request, QuestionModel $question, Comment $comment)
    {
        $user = $request->user();
        if ($user->id !== $comment->user_id) {
            return response()->json(['message' => [trans('plus-question::comments.not-owner')]], 403);
        }

        $question->getConnection()->transaction(function () use ($question, $comment, $user) {
            $comment->delete();
            $question->decrement('comments_count', 1);
            $user->extra()->decrement('comments_count', 1);
        });

        return response()->json()->setStatusCode(204);
    }

    /**
     * delete a comment of an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answer
     * @param  Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     */
    public function delAnswerComment(Request $request, AnswerModel $answer, Comment $comment)
    {
        $user = $request->user();
        if ($user->id !== $comment->user_id) {
            return response()->json(['message' => [trans('plus-question::comments.not-owner')]], 403);
        }

        $answer->getConnection()->transaction(function () use ($answer, $comment, $user) {
            $comment->delete();
            $answer->decrement('comments_count', 1);
            $user->extra()->decrement('comments_count', 1);
        });

        return response()->json()->setStatusCode(204);
    }
}
