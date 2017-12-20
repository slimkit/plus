<?php

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
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Observers;

use Zhiyi\Plus\Models\Comment;

class CommentObserver
{
    /**
     * The Comment created observer.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function created(Comment $comment)
    {
        $comment->user->extra()->firstOrCreate([])->increment('comments_count', 1);
    }

    /**
     * The comment deleted observer.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(Comment $comment)
    {
        $comment->user->extra()->decrement('comments_count', 1);
    }
}
