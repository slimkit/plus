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

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest;
use Zhiyi\Plus\Http\Requests\API2\StoreVerifyCode;
use Zhiyi\Plus\Models\VerificationCode;

class VerifyCodeController extends Controller
{
    /**
     * 创建注册验证码.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function storeByRegister(CreateRegisterVerifyCodeRequest $request)
    {
        return $this->sendFromRequest($request);
    }

    /**
     * 创建并发送用户验证码.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreVerifyCode $request [description]
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreVerifyCode $request)
    {
        return $this->sendFromRequest($request);
    }

    /**
     * 发送验证码通过请求
     *
     * @param Request $request
     * @return mixed
     */
    protected function sendFromRequest(Request $request)
    {
        $map = [
            'mail' => 'email',
            'sms' => 'phone',
        ];
        $user = $request->user()->id ?? null;

        foreach ($map as $channel => $input) {
            if (! ($account = $request->input($input))) {
                continue;
            }

            try {
                $this->send($account, $channel, [
                    'user_id' => $user,
                ]);
            } catch (\Exception $e) {
                \Log::error($e);

                return response()->json(['message' => '验证码发送失败'], 400);
            }
            break;
        }

        return response()->json(['message' => ['获取成功']], 202);
    }

    /**
     * Send phone or email verification code.
     *
     * @param  string  $account
     * @param  string  $channel
     * @param  array  $data
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function send(string $account, string $channel = '', array $data = [])
    {
        $this->validateSent($account);
        $data['account'] = $account;
        $data['channel'] = $channel;
        VerificationCode::query()->where('channel', $channel)
            ->where('account', $account)
            ->delete();
        $model = VerificationCode::factory()->create($data);
        $model->notify(
             new \Zhiyi\Plus\Notifications\VerificationCode($model)
        );
    }

    /**
     * Validate sent.
     *
     * @param string $account
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateSent(string $account)
    {
        $validSecond = config('app.env') == 'production' ? 60 : 6;
        $verify = VerificationCode::query()->where('account', $account)
            ->byValid($validSecond)
            ->orderBy('id', 'desc')
            ->first();

        if ($verify) {
            abort(403, sprintf('还需要%d秒后才能获取', $verify->makeSurplusSecond($validSecond)));
        }
    }
}
