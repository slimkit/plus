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
        if (! is_array($matches[1]) || empty($matches[1])) {
            return;
        }

        $users = UserModel::where(function ($query) use ($matches) {
            $query = $query->where('name', 'like', array_pop($matches[1]));
            foreach ($matches[1] as $username) {
                $query = $query->where('name', 'like', $username);
            }

            return $query;
        })->get();
        $message = app(Message::class);

        foreach ($users as $user) {
            $message->send($sender, $user, $resource);
        }
    }
}
