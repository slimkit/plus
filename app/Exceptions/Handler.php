<?php

namespace Zhiyi\Plus\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Prepare response containing exception render.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse($request, Exception $e)
    {
        if ($request->expectsJson()) {
            return $this->renderAPIsException($request, $e);
        }

        return parent::prepareResponse($request, $e);
    }

    /**
     * Render an exception into an HTTP response to json.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function renderAPIsException($request, Exception $e)
    {
        $hasAPIsV1 = $request->is('*/v1/*');
        if ($e instanceof HttpException) {
            return response()->json(
                $hasAPIsV1
                    ? [
                        'status'  => false,
                        'code'    => 0,
                        'message' => $e->getMessage() ?: Response::$statusTexts[$e->getStatusCode()],
                        'data'    => null,
                    ]
                    : ['message' => [$e->getMessage() ?: Response::$statusTexts[$e->getStatusCode()]]],
                $e->getStatusCode()
            );
        }

        return response()->json(
            $hasAPIsV1
                ? [
                    'status'  => false,
                    'code'    => 0,
                    'message' => $e->getMessage() ?: 'Unknown error.',
                    'data'    => null,
                ]
                : ['message' => [$e->getMessage() ?: 'Unknown error.']],
            500
        );
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('*/v1/*')) {
            return response()->json([
                'status'  => false,
                'code'    => 1099,
                'message' => '用户认证失败',
                'data'    => null,
            ], 401);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => ['Unauthenticated.'],
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
