<?php

namespace Zhiyi\Plus\Http\Middleware\V2;

use Closure;
use Validator;
use Illuminate\Http\Request;

class VerifyUserNameRole
{
    /**
     * 最大用户名长度.
     *
     * @var int
     */
    protected $usernameMaxLength = 48; // 4b * 12

    /**
     * 最小用户名长度.
     *
     * @var int
     */
    protected $usernameMinLength = 4;

    /**
     * 验证用户名规则是否合法.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $length = strlen($request->input('name'));

        if ($length > $this->usernameMaxLength || $length < $this->usernameMinLength) {
            return response()->json([
                'message' => '用户名长度不符合规范',
            ])->setStatusCode(400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|username',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '用户名格式不正确',
            ])->setStatusCode(400);
        }

        return $next($request);
    }
}
