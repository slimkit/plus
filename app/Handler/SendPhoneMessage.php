<?php

namespace App\Handler;

use App\Exceptions\MessageResponseBody;
use App\Models\VerifyCode;
use Symfony\Component\HttpFoundation\Response;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;

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

        var_dump($response);exit;

        return app(MessageResponseBody::class, [
            'status' => true,
        ]);
    }
}
