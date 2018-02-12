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

namespace SlimKit\PlusQuestion\Admin\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Zhiyi\Plus\Models\Comment as CommentModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;

class CommentController extends Controller
{
    /**
     * Get comments of questions and answers.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);
        $id = (int) $request->query('id');
        $user = (int) $request->query('user');
        $question = $request->query('question');
        $answerUser = $request->query('answer_user');
        $type = $request->query('type');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = CommentModel::when($id, function ($query) use ($id) { // 根据评论id查询
            return $query->where('id', $id);
        })
        ->when($user, function ($query) use ($user) { // 根据评论者筛选
            return $query->where('user_id', $user);
        })
        ->where(function ($query) use ($question, $answerUser) { // 根据所属问题或回答资源条件筛选
            return $query->where(function ($query) use ($question) { // 根据问题标题筛选
                return $query->when($question, function ($query) use ($question) {
                    return $query->where('commentable_type', 'questions')->whereExists(function ($query) use ($question) {
                        return $query->from('questions')->whereRaw('questions.id = comments.commentable_id')->where('id', $question);
                    });
                });
            })
            ->orWhere(function ($query) use ($answerUser) { // 根据回答者筛选
                return $query->when($answerUser, function ($query) use ($answerUser) {
                    return $query->where('commentable_type', 'question-answers')->whereExists(function ($query) use ($answerUser) {
                        return $query->from('answers')->whereRaw('answers.id = comments.commentable_id')->where('answers.user_id', $answerUser);
                    });
                });
            });
        })
        ->when($startDate, function ($query) use ($startDate) { // 根据起始时间筛选
            return $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) { // 根据截至时间筛选
            return $query->whereDate('created_at', '<=', $endDate);
        })
        ->when(($type && ! $question && ! $answerUser), function ($query) use ($type) { // 根据评论所属资源类型筛选
            switch ($type) {
                case 'question':
                    $typeKey = 'questions';
                    break;
                case 'answer':
                    $typeKey = 'question-answers';
                    break;
                default:
                    $typeKey = $type;
                    break;
            }

            return $query->where('commentable_type', $typeKey);
        })
        ->when((! $type && ! $question && ! $answerUser), function ($query) { // 未筛选时限制查询评论范围
            return $query->whereIn('commentable_type', ['questions', 'question-answers']);
        });

        $total = $query->count('id');
        $comments = $query->limit($limit)->offset($offset)->orderBy('id', 'desc')->with('user')->get();

        return response()->json($comments, 200, ['x-total' => $total]);
    }

    /**
     * Delete comment.
     *
     * @param CommentModel $comment
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function delete(CommentModel $comment)
    {
        DB::beginTransaction();
        try {
            switch ($comment->commentable_type) { // 删除对应资源评论统计
                case 'questions':
                    QuestionModel::where('id', $comment->commentable_id)->decrement('comments_count', 1);
                    break;
                case 'question-answers':
                    AnswerModel::where('id', $comment->commentable_id)->decrement('comments_count', 1);
                    break;
                default:
                    break;
            }

            $comment->user->extra ? $comment->user->extra->decrement('comments_count', 1) : $comment->user->extra()->firstOrCreate([]); // 用户评论统计
            $comment->delete();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->errorInfo,
            ])->setStatusCode(500);
        }
        DB::commit();

        return response(null, 204);
    }
}
