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

namespace Zhiyi\Plus\AtMessage;

use Zhiyi\Plus\Models\User as UserModel;

trait AtMessageHelperTrait
{
    /**
     * Send at message.
     * @param string $content
     * @param \Zhiyi\Plus\Models\User $sender
     * @param mixed $resource
     * @return void
     */
    public function sendAtMessage(string $content, UserModel $sender, $resource): void
    {
        preg_match_all('/\x{00ad}@((?:[^\/]+?))\x{00ad}/iu', $content, $matches);
        $users = UserModel::whereIn('name', $matches[1])->get();
        $message = app(Message::class);

        foreach ($users as $user) {
            $message->send($sender, $user, $resource);
        }
    }
}
