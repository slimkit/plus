<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace SlimKit\PlusID\Support;

class Message
{
    protected $status;
    protected $code;
    protected $data = [];

    public function __construct($code, $status, array $data = [])
    {
        $this->status = $status;
        $this->code = $code;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return array_merge($this->data, [
            'code' => $this->code,
            'status' => $this->status,
        ]);
    }
}
