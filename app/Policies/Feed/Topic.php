<?php

declare(strict_types=1);

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
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Policies\Feed;

use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;

class Topic
{
    /**
     * Check the topic can be operated by the user.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @param \Zhiyi\Plus\Models\FeedTopic $topic
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
