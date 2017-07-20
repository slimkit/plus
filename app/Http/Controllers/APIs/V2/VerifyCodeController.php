<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Zhiyi\Plus\Models\VerificationCode;
use Zhiyi\Plus\Http\Requests\API2\StoreVerifyCode;
use Zhiyi\Plus\Http\Requests\API2\CreateRegisterVerifyCodeRequest;

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

            $this->send($account, $channel, [
                'user_id' => $user,
            ]);
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
    protected function send(string $account, string $channel = '', array $data = [])
    {
        $this->validateSent($account);

        $data['account'] = $account;
        $data['channel'] = $channel;
        $model = factory(VerificationCode::class)->create($data);
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
        $vaildSecond = config('app.env') == 'production' ? 60 : 6;
        $verify = VerificationCode::where('account', $account)
            ->byValid($vaildSecond)
            ->orderBy('id', 'desc')
            ->first();

        if ($verify) {
            abort(403, sprintf('还需要%d秒后才能获取', $verify->makeSurplusSecond($vaildSecond)));
        }
    }
}
