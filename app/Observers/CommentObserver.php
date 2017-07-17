<?php

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
