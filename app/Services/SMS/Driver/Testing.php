<?php

namespace Zhiyi\Plus\Services\SMS\Driver;

use Zhiyi\Plus\Services\SMS\Message;
use Zhiyi\Plus\Services\SMS\DirverInterface;

class Testing implements DirverInterface
{
    protected $config;

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function send(Message $message)
    {
        info($message->getMessage());
    }
}
