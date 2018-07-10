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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The app function alise.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed|\Illuminate\Foundation\Application
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function app($abstract = null, array $parameters = [])
    {
        return app($abstract, $parameters);
    }

    /**
     * The response function alise.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function response(...$arguments)
    {
        return response(...$arguments);
    }

    /**
     * The request function alise.
     *
     * @param array|string $key
     * @param mixed $default
     * @return \Illuminate\Http\Request|string|array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function request($key = null, $default = null)
    {
        return request($key, $default);
    }
}
