<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Notifications\Messages;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Illuminate\Config\Repository as ConfigRepository;

class VerificationCodeMessage extends Message
{
    protected $config;
    protected $code;

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
        $alias = $this->gatewayAliasName($gateway);

        return str_replace(':code', $this->code, $this->config->get($alias.'.content'));
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
        $alias = $this->gatewayAliasName($gateway);

        return $this->config->get($alias.'.template');
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
        $alias = $this->gatewayAliasName($gateway);

        return [
            $this->config->get($alias.'.:code') => (string) $this->code,
        ];
    }

    /**
     * Get Gateway Alias name.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface $gateway
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function gatewayAliasName(GatewayInterface $gateway = null): string
    {
        foreach (config('sms.gateway_aliases') as $alias => $class) {
            if ($gateway instanceof $class) {
                return $alias;
            }
        }

        throw new \Exception('不支持的网关');
    }
}
