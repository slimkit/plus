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

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;

class UserEmailController extends Controller
{
    /**
     * 解除用户 E-Mail 绑定.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Request $request, ResponseFactoryContract $response)
    {
        $rules = [
            'verifiable_code' => 'required|string',
            'password' => 'required|string',
        ];
        $this->validate($request, $rules);

        $verifiable_code = $request->input('verifiable_code');
        $password = $request->input('password');
        $user = $request->user();
        $verify = VerificationCodeModel::where('channel', 'mail')
            ->where('account', $user->email)
            ->where('code', $verifiable_code)
            ->orderby('id', 'desc')
            ->first();

        if (! $user->verifyPassword($password)) {
            return $response->json(['message' => ['密码错误']], 422);
        } elseif (! $verify) {
            return $response->json(['message' => ['验证码错误或者已失效']], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $verify) {
            $user->email = null;
            $user->save();
            $verify->delete();
        });

        return $response->make('', 204);
    }
}
