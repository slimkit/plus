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

namespace SlimKit\PlusID\Actions;

use SlimKit\PlusID\Server;
use Illuminate\Http\Request;
use SlimKit\PlusID\Support\URL;
use SlimKit\PlusID\Support\Message;
use SlimKit\PlusID\Models\Client as ClientModel;

class Action
{
    protected $request;
    protected $server;
    protected $client;

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function setServer(Server $server)
    {
        $this->server = $server;

        return $this;
    }

    public function setClient(ClientModel $client)
    {
        $this->client = $client;

        return $this;
    }

    protected function mode(): string
    {
        if ((bool) $this->request->redirect) {
            return 'redirect';
        } elseif ((bool) $this->request->callback) {
            return 'jsonp';
        }

        return 'json';
    }

    protected function response(Message $message, string $mode = '')
    {
        $mode = $mode ?: $this->mode();

        $sign = $this->request->sign;
        $minutes = \Illuminate\Support\Carbon::now()->addSeconds(300);
        cache([$sign => true], $minutes);

        if ($mode === 'json') {
            return response()->json(
                $message->toArray()
            );
        } elseif ($mode === 'jsonp') {
            return response()->jsonp($this->request->callback, $message->toArray());
        }

        $url = new URL($this->request->redirect);
        foreach ($message->toArray() as $key => $value) {
            $url->addQuery($key, $value);
        }

        return redirect((string) $url, 302);
    }

    public function check()
    {
        $status = [
            $this->checkTimeOut(),
            $this->checkSign(),
        ];
        $status[] = ! cache($this->request->sign);

        foreach ($status as $check) {
            if ($check !== true) {
                return $check;
            }
        }

        return true;
    }

    protected function checkTimeOut()
    {
        $time = $this->request->time;

        // 过期 五分钟 5 * 60
        if ((time() - $time) > 300) {
            return $this->response(
                new Message(10000, 'fail')
            );
        }

        return true;
    }

    protected function checkSign()
    {
        $action = $this->getSignAction();

        // 增加五分钟有效期的时间验证. 保证不会被伪造请求。
        $action['tc'] = floor(time() / 300);

        if ($this->request->sign === $this->server->sign($action)) {
            return true;
        }

        return $this->response(
            new Message(10001, 'fail')
        );
    }
}
