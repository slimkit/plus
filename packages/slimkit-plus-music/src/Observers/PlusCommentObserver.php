<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Observers;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment;

class PlusCommentObserver
{
    /**
     * Global Comment deleted.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(Comment $comment)
    {
        return $this->validateOr($comment, function (MusicComment $musicComment) {
            $musicComment->delete();
        });
    }

    /**
     * Fetch event.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function fetch(Comment $comment)
    {
        return $this->validateOr($comment, function (MusicComment $musicComment) {
            return new Fetch\CommentFetch($musicComment);
        });
    }

    /**
     * Validate or run call.
     *
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @param callable $call
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateOr(Comment $comment, callable $call)
    {
        if (! in_array($comment->channel, ['music', 'music_special']) || ! ($comment = MusicComment::find($comment->target))) {
            return null;
        }

        return call_user_func_array($call, [$comment]);
    }
}
