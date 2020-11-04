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

namespace Zhiyi\Plus\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zhiyi\Plus\API2\Requests\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Tymon\JWTAuth\Exceptions\JWTException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     * @throws \Throwable
     */
    public function register()
    {
        // parent::report();
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  $request
     * @param  \Throwable  $exception
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function render($request, \Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Validation\ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        return parent::invalidJson($request, new ValidationException($exception));
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $exception
     * @return \Exception
     */
    protected function prepareException(\Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException('内容不存在', $exception);
        }

        return parent::prepareException($exception);
    }
}
