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

namespace SlimKit\PlusID;

use Illuminate\Http\Request;
use SlimKit\PlusID\Models\Client as ClientModel;

class Server
{
    protected $client;
    protected $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function setClient(ClientModel $client)
    {
        $this->client = $client;

        return $this;
    }

    public function dispatch(Request $request, string $action)
    {
        $action = $this->action->action($action)
            ->setRequest($request)
            ->setServer($this)
            ->setClient($this->client);

        if (($response = $action->check()) !== true) {
            return $response;
        }

        return $action->dispatch();
    }

    public function sign(array $action): string
    {
        return $this->client->sign($action);
    }
}
