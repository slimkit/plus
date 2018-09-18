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

class Action
{
    protected $actions = [
        'auth/resolve' => \SlimKit\PlusID\Actions\Auth\Resolve::class,
        'auth/login' => \SlimKit\PlusID\Actions\Auth\Login::class,
        'user/check' => \SlimKit\PlusID\Actions\User\Check::class,
        'user/create' => \SlimKit\PlusID\Actions\User\Create::class,
        'user/delete' => \SlimKit\PlusID\Actions\User\Delete::class,
        'user/show' => \SlimKit\PlusID\Actions\User\Show::class,
        'user/update' => \SlimKit\PlusID\Actions\User\Update::class,
    ];

    public function action(string $action)
    {
        if (isset($this->actions[$action])) {
            return new $this->actions[$action];
        }

        abort(404, '不存在的 API.');
    }
}
