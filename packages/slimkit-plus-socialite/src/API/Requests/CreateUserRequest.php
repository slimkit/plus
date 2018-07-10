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

namespace SlimKit\PlusSocialite\API\Requests;

use Illuminate\Validation\Rule;

class CreateUserRequest extends AccessTokenRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => [
                'required',
                'string',
                'username',
                'display_length:2,12',
                Rule::notIn(config('site.reserved_nickname')),
                'unique:users,name',
            ],
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'name.required' => '请输入用户名',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.display_length' => '用户名长度不合法',
            'name.unique' => '用户名已经被其他用户所使用',
            'name.not_in' => '系统保留用户名，禁止使用',
        ]);
    }
}
