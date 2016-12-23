<?php

namespace App\Handler;

use App\Exceptions\MessageResponseBody;
use App\Models\VerifyCode;
use Symfony\Component\HttpFoundation\Response;

class SendPhoneMessage
{
    protected $verify;

    public function __construct(VerifyCode $verify)
    {
        $this->verify = $verify;
    }

    public function send(): Response
    {
        return app(MessageResponseBody::class, [
            'status' => true,
        ]);
    }
}
