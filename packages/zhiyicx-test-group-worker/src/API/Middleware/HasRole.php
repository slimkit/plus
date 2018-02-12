<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class HasRole
{
    protected $auth;

    /**
     * Create the middleware.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * The middleware handle.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            abort(401, '请登录');
        }

        $user = $this->auth->user();
        if (! $user->roles('developer') && ! $user->roles('tester')) {
            abort(403, '你没有请求权限');
        }

        return $next($request);
    }
}
