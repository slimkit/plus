<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use Closure;
use Exception;

class ApiMessageResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
        } finally {
            if ($response->exception) {
                return app(MessageResponseBody::class, [
                    'status'  => false,
                    'message' => $response->exception->getMessage(),
                    'data'    => config('app.debug') ? $response->exception->getTraceAsString() : '',
                ]);
            }
        }

        return $response;
    }
}
