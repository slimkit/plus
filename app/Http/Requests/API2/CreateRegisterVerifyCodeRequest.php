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

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

class CreateRegisterVerifyCodeRequest extends FormRequest
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
            'phone' => 'required_without:email|cn_phone|unique:users,phone',
            'email' => 'required_without:phone|email|max:128|unique:users,email',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'phone.required_without' => '请求的手机号码不能为空',
            'phone.cn_phone' => '请求的手机号码必须是大陆地区合法手机号码',
            'phone.unique' => '手机号码已经被使用，不能发送验证码',

            'email.required_without' => '请求的邮箱地址不能为空',
            'email.email'  => '请求的邮箱地址格式无效',
            'email.max'    => '请求的邮箱地址太长，应小于128字节',
            'email.unique' => '邮箱地址已经被使用，不能发送验证码',
        ];
    }
}
