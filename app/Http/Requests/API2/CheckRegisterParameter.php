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

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

class CheckRegisterParameter extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|cn_phone',
            'password' => 'required',
            'name' => 'username|min:4|max:48',
        ];
    }

    /**
     * return validation messages.
     *
     * @author bs<414606094@qq.com>
     */
    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.cn_phone' => '请输入中国大陆合法手机号码',
            'password.required' => '密码不能为空',
            'name.username' => '请输入格式正确的用户名',
            'name.min' => '用户名最小长度不小于4位',
            'name.max' => '用户名最大长度不超过48位',
        ];
    }
}
