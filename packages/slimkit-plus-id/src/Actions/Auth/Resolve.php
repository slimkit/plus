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

namespace SlimKit\PlusID\Actions\Auth;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;

class Resolve extends Action
{
    protected function getSignAction(): array
    {
        return [
            'action' => 'auth/resolve',
            'app' => $this->client->id,
            'time' => (int) $this->request->time,
        ];
    }

    public function check()
    {
        if (($response = parent::check()) !== true) {
            return $response;
        }

        if ($this->request->user('web') === null) {
            return $this->response(new Message(10002, 'fail'));
        }

        return true;
    }

    public function dispatch()
    {
        $action = [
            'app' => $this->client->id,
            'action' => 'auth/resolve',
            'user' => $user = $this->request->user('web')->id,
            'time' => $time = time(),
            'tc' => floor($time / 300),
        ];

        return $this->response(new Message(200, 'success', [
            'sign' => $this->server->sign($action),
            'user' => $user,
            'time' => $time,
        ]));
    }
}
