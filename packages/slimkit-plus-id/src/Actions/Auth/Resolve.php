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
