<?php

namespace Zhiyi\Plus\Notifications\Messages;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Illuminate\Config\Repository as ConfigRepository;

class VerificationCodeMessage extends Message
{
    protected $config;
    protected $code;
    protected $gateways = ['alidayu'];

    /**
     * Create the message instance.
     *
     * @param \Illuminate\Config\Repository $config
     * @param int $code
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ConfigRepository $config, int $code)
    {
        $this->config = $config;
        $this->code = $code;
    }

    /**
     * Get the message content.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getContent(GatewayInterface $gateway = null)
    {
        return sprintf('验证码%s，如非本人操作，请忽略本条信息。', $this->code);
    }

    /**
     * Get the message template.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTemplate(GatewayInterface $gateway = null)
    {
        return $this->config->get('alidayu.template');
    }

    /**
     * Get the message data.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getData(GatewayInterface $gateway = null)
    {
        return [
            'code' => strval($this->code),
        ];
    }
}
