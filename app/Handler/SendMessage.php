<?php

namespace Zhiyi\Plus\Handler;

use Symfony\Component\HttpFoundation\Response;
use Zhiyi\Plus\Models\VerifyCode;

class SendMessage
{
    protected $verify;
    protected $type;

    /**
     * 构造方法.
     *
     * @param VerifyCode $verify 验证数据对象
     * @param string     $type   发送消息的类型，[phone|email]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function __construct(VerifyCode $verify, string $type)
    {
        $this->verify = $verify;
        $this->type = strtolower($type);
    }

    /**
     * 发送消息.
     *
     * @return Response 消息对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function send(): Response
    {
        return call_user_func([$this, 'with'.ucfirst($this->type)]);
    }

    public function withPhone(): Response
    {
        $message = new SendPhoneMessage($this->verify);

        return $message->send();
    }

    public function withEmail(): Response
    {
        $message = new SendEmailMessage($this->verify);

        return $message->send();
    }
}
