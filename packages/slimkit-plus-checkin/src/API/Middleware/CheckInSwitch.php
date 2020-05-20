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

namespace SlimKit\PlusCheckIn\API\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use function Zhiyi\Plus\setting;

class CheckInSwitch
{
    /**
     * The middleware handle.
     *
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        if (! setting('checkin', 'switch', false)) {
            throw new AuthorizationException(
                trans('plus-checkin::messages.checkin-closed')
            );
        }

        return $next($request);
    }
}
