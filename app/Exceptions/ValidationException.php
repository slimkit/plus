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

namespace Zhiyi\Plus\Exceptions;

use Illuminate\Validation\ValidationException as IlluminateValidationException;

class ValidationException extends IlluminateValidationException
{
    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Validation\ValidationException $exception
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(IlluminateValidationException $exception)
    {
        parent::__construct($exception->validator, $exception->response, $exception->errorBag);

        $this->status = $exception->status;
        $this->redirectTo = $exception->redirectTo;
        $this->message = trans('validation.message');
    }
}
