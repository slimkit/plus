<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Comment as CommentModel;

class FeedPinned extends Model
{
    /**
     * Has user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     *  Has feed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function feed()
    {
        if ($this->channel === 'comment') {
            return $this->hasOne(Feed::class, 'id', 'raw');
        }

        return $this->hasOne(Feed::class, 'id', 'target');
    }

    /**
     * Has feed comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function comment()
    {
        return $this->hasOne(CommentModel::class, 'id', 'target');
    }
}
