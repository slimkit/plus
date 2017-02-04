<?php

namespace Zhiyi\Plus\Handler;

use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\Models\VerifyCode;

class SendPhoneMessage
{
    protected $verify;
    protected $alidayu;

    public function __construct(VerifyCode $verify)
    {
        $this->verify = $verify;

        $app = new App(config('alidayu'));
        $this->alidayu = new Client($app);
    }

    public function send(): Response
    {
        $freeSignName = config('alidayu.sign_name');
        $verifyTemplateId = config('alidayu.verify_template_id');

        $request = new AlibabaAliqinFcSmsNumSend();
        $request->setSmsParam([
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

            return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '发送成功',
                'data'    => null,
            ])->setStatusCode(201);
        }

        return response()->json([
            'status'  => false,
            'code'    => 1009,
            'message' => $sub_msg ?: null,
            'data'    => [$result, $sub_code, $sub_msg],
        ])->setStatusCode(503);
    }
}
