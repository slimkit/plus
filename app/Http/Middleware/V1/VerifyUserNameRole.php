<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Validator;
use Illuminate\Http\Request;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class VerifyUserNameRole
{
    use CreateJsonResponseData;
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
            return response()->json(static::createJsonData([
                'code' => 1002,
            ]))->setStatusCode(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|username',
        ]);

        if ($validator->fails()) {
            return response()->json(static::createJsonData([
                'code' => 1003,
            ]))->setStatusCode(403);
        }

        return $next($request);
    }
}
