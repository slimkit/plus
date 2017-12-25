<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Observers;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment;

class CommentObserver
{
    /**
     * Feed comment created.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment $musicComment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function created(MusicComment $musicComment)
    {
        $comment = new Comment();
        $comment->user_id = $musicComment->user_id;
        $comment->target_user = 0;
        $comment->reply_user = $musicComment->reply_to_user_id ?: 0;
        $comment->target = $musicComment->id;
        if ($musicComment->music_id > 0) {
            $comment->channel = 'music';
        } else {
            $comment->channel = 'music_special';
        }
        $comment->save();
    }

    /**
     * Feed comment deleted.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment $musicComment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(MusicComment $musicComment)
    {
        if ($musicComment->music_id > 0) {
            Comment::where('channel', 'music')
                ->where('target', $musicComment->id)
                ->delete();
        } else {
            Comment::where('channel', 'music_special')
                ->where('target', $musicComment->id)
                ->delete();
        }

    }
}
