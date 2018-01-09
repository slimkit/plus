<?php declare(strict_types=1);

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

namespace Zhiyi\Plus\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class CrossDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $response instanceof Response) {
            return $response;
        }

        $response->headers->set('Access-Control-Allow-Credentials', $this->getCredentials());
        // $response->headers->set('Access-Control-Allow-Methods', implode(', ', ['GET', 'POST', 'PATCH', 'PUT', 'OPTIONS']));
        $response->headers->set('Access-Control-Allow-Methods', '*');
        // $response->headers->set('Access-Control-Allow-Headers', implode(', ', ['Origin', 'Content-Type', 'Accept', 'Cookie']));
        $response->headers->set('Access-Control-Allow-Headers', '*');
        $response->headers->set('Access-Control-Allow-Origin', $this->getOrigin($request));

        return $response;
    }

    protected function getCredentials(): string
    {
        $credentials = config('http.cros.credentials');
        if ($credentials) {
            return 'true';
        }

        return 'false';
    }

    protected function getOrigin($request): string
    {
        $origin = config('http.cros.origin');
        if ($origin === '*') {
            return '*';
        }

        $requestOrigin = $request->headers->get('origin');
        if (in_array($requestOrigin, (array) $origin)) {
            return $requestOrigin;
        }

        return (string) '';
    }
}
