<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ParseResourceException extends HttpException
{
    /**
     * Create a parse resource exception.
     */
    public function __construct()
    {
        parent::__construct(500, '文件资源地址解析失败或者是不支持的资源');
    }
}
