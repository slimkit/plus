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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
