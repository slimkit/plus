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

namespace Zhiyi\Plus\FileStorage\Validators;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

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
