<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageResponseBody;
use App\Models\AuthToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AuthUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->headers->get('ACCESS-TOKEN');

        if (!$accessToken) {
            return app(MessageResponseBody::class, [
                'code' => 1016,
            ]);
        }

        $authToken = AuthToken::byToken($accessToken)
            ->withTrashed()
            ->orderByDesc()
            ->first();

        if (!$authToken) {
            return app(MessageResponseBody::class, [
                'code' => 1016,
            ]);

        // 判断token状态是否被下线
        } elseif ($authToken->state === 1) {
            return app(MessageResponseBody::class, [
                'code' => 1015,
            ]);

        // 判断token是否被过期
        } elseif (
            $authToken->deleted_at ||
            (
                $authToken->expires &&
                $authToken->created_at->diffInSeconds(Carbon::now()) >= $authToken->expires
            )
        ) {
            return app(MessageResponseBody::class, [
                'code' => 1012,
            ]);

        // 如果用户不存在
        } elseif (!$authToken->user) {
            return app(MessageResponseBody::class, [
                'code' => 1005,
            ]);
        }

        return $next($request);
    }
}
