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

namespace Zhiyi\Plus\FileStorage\Validators;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Validator;

abstract class AbstractValidator implements ValidatorInterface
{
    use ValidatesRequests {
        validate as private __validate__;
    }

    /**
     * The Validator validate handle.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function validate(Request $request): void
    {
        $this->__validate__($request, $this->rules(), $this->messages(), $this->customAttributes());
    }

    /**
     * get The validator rules.
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get the validate error messages.
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get the validate attribute custom name.
     * @return array
     */
    public function customAttributes(): array
    {
        return [];
    }
}
