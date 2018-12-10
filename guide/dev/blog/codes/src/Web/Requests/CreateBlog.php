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

namespace SlimKit\Plus\Packages\Blog\Web\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBlog extends FormRequest
{
    /**
     * Get request authorize.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom message from validateor rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'min:4', 'max:26', 'regex:/[a-z][a-z0-9]/s', Rule::unique('blogs', 'slug')],
            'name' => ['required', 'string', 'max:100'],
            'desc' => ['nullable', 'string', 'max:250'],
            'logo' => ['nullable', 'file', 'image'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'slug.regex' => ':attribute必须是字母开头仅含有字母和数字的小写字符串',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'slug' => '博客唯一标识',
            'name' => '博客名称',
            'desc' => '博客描述',
            'logo' => '博客图标',
        ];
    }
}
