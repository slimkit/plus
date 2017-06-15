<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\SMS\SMS;
use Zhiyi\Plus\Models\VerifyCode;
use Illuminate\Support\Facades\Mail;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Mail\VerifyCode as MailVerifyCode;
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
        foreach (['phone', 'email'] as $type) {
            if (! ($account = $request->input($type))) {
                continue;
            }

            $this->send($account, $type);
            break;
        }

        return response()->json(['message' => ['获取成功']], 202);
    }

    /**
     * Send phone or email verification code.
     *
     * @param string $account
     * @param string $type
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function send(string $account, string $type = '')
    {
        $this->validateSent($account);

        $verify = factory(VerifyCode::class)->create(['account' => $account]);
        if ($type === 'email' || strpos($account, '@') !== false) {
            Mail::to($account)->queue(new MailVerifyCode($verify));
        } else {
            $this->sms->dispatch($verify);
        }
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
        $vaildSecond = config('app.env') == 'production' ? 60 : 6;
        $verify = VerifyCode::byAccount($account)
            ->byValid($vaildSecond)
            ->orderBy('id', 'desc')
            ->first();

        if ($verify) {
            abort(403, sprintf('还需要%d后才能获取', $verify->makeSurplusSecond($vaildSecond)));
        }
    }
}
