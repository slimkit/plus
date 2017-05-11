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

    public function send($phone, array $data = [])
}
