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

namespace SlimKit\PlusQuestion\Observers;

use DB;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;

class QuestionObserver
{
    /**
     * 监听删除问题事件。
     *
     * @param QuestionModel $question
     * @return void
     */
    public function deleting(QuestionModel $question)
    {
        DB::transaction(function ($db) use ($question) {
            // 删除围观
            $db->table('question_watcher')
               ->where('question_id', $question->id)
               ->delete();
            // 删除邀请
            $db->table('question_invitation')
                ->where('question_id', $question->id)
                ->delete();
            // 删除问答评论
            $db->table('comments')
               ->where('commentable_id', $question->id)
               ->where('commentable_type', 'questions')
               ->delete();
            // 删除问答回答评论
            $db->table('comments')
                ->whereIn('commentable_id', $question->answers->map->id ?? [])
                ->where('commentable_type', 'answers')
                ->delete();
            // 删除问答
            $db->table('answers')
                ->where('question_id', $question->id)
                ->delete();
            // 删除精选
            $db->table('question_application')
               ->where('question_id', $question->id)
               ->delete();
            // 减少话题下问题数量
            $question->topics->map(function ($topic) {
                $topic->questions_count && $topic->decrement('questions_count');
            });
        });
    }
}
