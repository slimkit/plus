<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Plus\Models\User;

class NewsPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the news.
     *
     * @param  \Zhiyi\Plus\Models\User  $user
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News  $news
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
