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

namespace Zhiyi\Plus\Http\Middleware;

use Closure;

class SensitiveOperation
{
    /**
     * 对敏感操作验证用户密码是否正确.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (! $request->has('password') || ! $user->verifyPassword($request->input('password'))) {
            return response()->json(['message' => ['账户验证失败']], 403);
        }

        return $next($request);
    }
}
