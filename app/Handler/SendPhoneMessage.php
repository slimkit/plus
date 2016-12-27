<?php

namespace App\Handler;

use App\Exceptions\MessageResponseBody;
use App\Models\VerifyCode;
use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Symfony\Component\HttpFoundation\Response;

class SendPhoneMessage
{
    protected $verify;
    protected $alidayu;

    public function __construct(VerifyCode $verify)
    {
        $this->verify = $verify;
        $this->alidayu = app(Client::class, [
            app(App::class, [
                config('alidayu'),
            ]),
        ]);
    }

    public function send(): Response
    {
        $freeSignName = config('alidayu.sign_name');
        $verifyTemplateId = config('alidayu.verify_template_id');
        $request = with(new AlibabaAliqinFcSmsNumSend())
            ->setSmsParam([
                'code' => $this->verify->code,
            ])
            ->setSmsFreeSignName($freeSignName)
            ->setSmsTemplateCode($verifyTemplateId)
            ->setRecNum($this->verify->account);

        $response = $this->alidayu->execute($request);

        $result = isset($response->result) ? $response->result : null;
        $sub_code = isset($response->sub_code) ? $response->sub_code : null;
        $sub_msg = isset($response->sub_msg) ? $response->sub_msg : null;

        if ($result && $result->success == true) {

            // 发送成功～更新数据库验证状态
            $this->verify->state = 1;
            $this->verify->save();

            return app(MessageResponseBody::class, [
                'status' => true,
                'message' => '发送成功',
            ]);
        }

        return app(MessageResponseBody::class, [
            'status'  => false,
            'code'    => 1009,
            'message' => $sub_msg ?: '',
            'data'    => [$result, $sub_code, $sub_msg],
        ]);
    }
}
