<?php

namespace Zhiyi\Plus\Services\SMS;

interface DirverInterface
{
    /**
     * Set config.
     *
     * @param array $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setConfig(array $config);

    /**
     * Send handle.
     *
     * @param \Zhiyi\Plus\Services\SMS\Message $message
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function send(Message $message);
}
