<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Services\SMS\SMS;
use Zhiyi\Plus\Models\VerifyCode;
use Illuminate\Database\Eloquent\Factory;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest;

class VerifyCodeController extends Controller
{
    /**
     * 创建注册验证码.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Services\SMS\SMS $sms
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createRegister(CreateRegisterVerifyCodeRequest $request, ResponseFactory $response, SMS $sms)
    {
        $vaildSecond = config('app.env') == 'production' ? 300 : 6;
        $phone = $request->input('phone');

        $verify = VerifyCode::byAccount($phone)->byValid($vaildSecond)->orderByDesc()->first();

        if ($verify) {
            return $response->json([
                'message' => [sprintf('还需要%d后才能获取', $verify->makeSurplusSecond($vaildSecond))],
            ], 403);
        }

        return $this->createVerifyCode($phone, $sms, $response);
    }

    /**
     * 创建验证码并派发事件.
     *
     * @param string $phone
     * @param \Zhiyi\Plus\Services\SMS\SMS $sms
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createVerifyCode($phone, SMS $sms, ResponseFactory $response)
    {
        $sms->dispatch(factory(VerifyCode::class)->create([
            'account' => $phone,
        ]));

        return $response->json(['message' => ['获取成功']])->setStatusCode(201);
    }
}
