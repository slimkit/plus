<?php

namespace Zhiyi\Plus\Services\SMS\Dirver;

use Zhiyi\Plus\Services\SMS\Message;
use Zhiyi\Plus\Services\SMS\DirverInterface;

class Testing implements DirverInterface
{
    protected $config;

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function send($phone, Message $message)
    {
    }
}
