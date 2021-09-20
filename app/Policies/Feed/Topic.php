<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Policies\Feed;

use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Zhiyi\Plus\Models\User as UserModel;

class Topic
{
    /**
     * Check the topic can be operated by the user.
     *
     * @param  \Zhiyi\Plus\Models\User  $user
     * @param  \Zhiyi\Plus\Models\FeedTopic  $topic
     * @return bool
     */
    public function update(UserModel $user, FeedTopicModel $topic): bool
    {
        if ($user->ability('admin: update feed topic')) {
            return true;
        }

        return $user->id === $topic->creator_user_id;
    }
}
