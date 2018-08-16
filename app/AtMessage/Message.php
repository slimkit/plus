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

use Closure;
use Zhiyi\Plus\Services\Push;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\AtMessage as Model;
use Zhiyi\Plus\Models\UserCount as UserCountModel;

class Message implements MessageInterface
{
    /**
     * Resources manager.
     * @var \Zhiyi\Plus\AtMessage\ResourceManagerInterface
     */
    protected $manager;

    /**
     * At message model.
     * @var \Zhiyi\Plus\Models\AtMessage
     */
    protected $model;

    /**
     * Jpush pusher.
     * @var \Zhiyi\Plus\Services\Push
     */
    protected $pusher;

    /**
     * Create the message instance.
     * @param \Zhiyi\Plus\AtMessage\ResourceManagerInterface $manager
     * @param \Zhiyi\Plus\Models\AtMessage $model
     * @param \Zhiyi\Plus\Services\Push $pusher
     */
    public function __construct(ResourceManagerInterface $manager, Model $model, Push $pusher)
    {
        $this->manager = $manager;
        $this->model = $model;
        $this->pusher = $pusher;
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
        $atMessage = $this->message($resource, $user);
        $this->save(function () use ($resource, $atMessage, $user) {
            $atMessage->save();
            $this->updateAtMessageCount($user);
        });

        $this->notice($user, $resource);
    }

    /**
     * Create a at message model.
     * @param \Zhiyi\Plus\AtMessage\ResourceInterface $resource
     * @param \Zhiyi\Plus\Models\User $user
     * @return \Zhiyi\Plus\Models\AtMessage
     */
    public function message(ResourceInterface $resource, UserModel $user): Model
    {
        $message = $this->model->newInstance();
        $message->resourceable_type = $resource->type();
        $message->resourceable_id = $resource->id();
        $message->user_id = $user->id;

        return $message;
    }

    /**
     * Send a jpush message.
     * @param \Zhiyi\Plus\Models\User $user
     * @param \Zhiyi\Plus\AtMessage\ResourceInterface $resource
     * @return void
     */
    protected function notice(UserModel $user, ResourceInterface $resource): void
    {
        $this->pusher->push(
            $resource->message(),
            $user->id,
            [
                'resourceable_type' => $resource->type(),
                'resourceable_id' => $resource->id(),
            ]
        );
    }

    /**
     * Update user unread message count.
     * @param \Zhiyi\Plus\Models\User $user;
     * @return void
     */
    protected function updateAtMessageCount(UserModel $user): void
    {
        $model = new UserCountModel();
        $count = $model->newQuery()
            ->where('user_id', $user->id)
            ->where('type', 'at')
            ->first();
        if (! $count) {
            $count = $model->newInstance();
            $count->type = 'at';
            $count->user_id = $user->id;
            $count->total = 0;
        }

        $count->total += 1;
        $count->save();
    }

    /**
     * Open a database transaction.
     * @param \Closure $closure
     * @return any
     */
    protected function save(Closure $closure)
    {
        return $this->model->getConnection()->transaction($closure);
    }
}
