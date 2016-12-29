<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\MessageResponseBody;

class AuthUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->headers->get('ACCESS-TOKEN');

        if (!$accessToken) {
            return app(MessageResponseBody::class, [
                'code' => 1014,
            ]);
        }

        return $next($request);
    }
}
