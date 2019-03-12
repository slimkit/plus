<?php

namespace Zhiyi\Plus\Policies;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the news.
     *
     * @param  \Zhiyi\Plus\Models\User  $user
     * @param  \Zhiyi\Plus\Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News  $news
     * @return mixed
     */
    public function delete(User $user, News $news)
    {
        if ($user->id === $news->user_id) {
            return true;
        } elseif ($user->ability('[News] Delete News Post')) {
            return true;
        }

        return false;
    }
}
