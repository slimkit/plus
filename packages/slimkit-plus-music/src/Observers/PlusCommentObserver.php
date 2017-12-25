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
