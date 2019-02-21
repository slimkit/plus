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
use Zhiyi\Plus\Notifications\At as AtNotification;

class Message implements MessageInterface
{
    /**
     * Resources manager.
     * @var \Zhiyi\Plus\AtMessage\ResourceManagerInterface
     */
    protected $manager;

    /**
     * Create the message instance.
     * @param \Zhiyi\Plus\AtMessage\ResourceManagerInterface $manager
     */
    public function __construct(ResourceManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * The message send handler.
     * @param \Zhiyi\Plus\Models\User $sender
     * @param \Zhiyi\Plus\Models\User $user
     * @param mixed $resource
     * @return void
     */
    public function send(UserModel $sender, UserModel $user, $resource): void
    {
        $resource = $this->manager->resource($resource, $sender);
        $user->notify(new AtNotification($resource, $sender));
    }
}
