<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Services\SMS\SMS;
use Zhiyi\Plus\Models\VerifyCode;
use Illuminate\Database\Eloquent\Factory;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreVerifyCode;
use Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest;

class VerifyCodeController extends Controller
{
    protected $sms;

    /**
     * 设置启动需要依赖.
     *
     * @param \Zhiyi\Plus\Services\SMS\SMS $sms
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(SMS $sms)
    {
        $this->sms = $sms;
    }

    /**
     * 创建注册验证码.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function storeByRegister(CreateRegisterVerifyCodeRequest $request)
    {
        return $this->send(
            $request->input('phone')
        );
    }

    /**
     * 创建用户短信.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreVerifyCode $request [description]
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreVerifyCode $request)
    {
        return $this->send(
            $request->input('phone')
        );
    }

    /**
     * Send phone message.
     *
     * @param string $phone
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function send($phone)
    {
        $vaildSecond = config('app.env') == 'production' ? 60 : 6;
        $verify = VerifyCode::byAccount($phone)->byValid($vaildSecond)
            ->orderByDesc()
            ->first();

        if ($verify) {
            return response()->json(['message' => [sprintf('还需要%d后才能获取', $verify->makeSurplusSecond($vaildSecond))]])
                ->setStatusCode(403);
        }

        $this->sms->dispatch(factory(VerifyCode::class)->create(['account' => $phone]));

        return response()->json(['message' => ['获取成功']])->setStatusCode(201);
    }
}
