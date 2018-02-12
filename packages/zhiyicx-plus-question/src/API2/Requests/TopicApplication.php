<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace SlimKit\PlusQuestion\API2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicApplication extends FormRequest
{
    /**
     * @author bs<414606094@qq.com>
     * @return mixed
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 验证规则.
     *
     * @author bs<414606094@qq.com>
     * @return mixed
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }

    /**
     * 验证提示信息.
     *
     * @author bs<414606094@qq.com>
     * @return mixed
     */
    public function messages(): array
    {
        return [
            'name.required' => '话题名称不能为空',
            'body.max' => '不能超过255个字',
            'description.required' => '话题描述不能为空',
        ];
    }
}
