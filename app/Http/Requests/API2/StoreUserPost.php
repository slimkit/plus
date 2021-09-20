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
use Illuminate\Validation\Rule;

class StoreUserPost extends FormRequest
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
            'phone'           => 'required_without:email|cn_phone|unique:users,phone',
            'email'           => 'required_without:phone|email|max:128|unique:users,email',
            'password'        => 'nullable|string',
            'verifiable_code' => 'required',
            'verifiable_type' => 'required|string|in:mail,sms',
            'name'            => [
                'required',
                'username',
                'display_length:2,12',
                Rule::notIn(config('site.reserved_nickname')),
                'unique:users,name',
            ],
        ];
    }

    /**
     * Get rule messages.
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'phone.required_without'   => '请输入用户手机号码',
            'phone.cn_phone'           => '请输入大陆地区合法手机号码',
            'phone.unique'             => '手机号码已经存在',
            'email.required_without'   => '请输入用户邮箱地址',
            'email.email'              => '请输入有效的邮箱地址',
            'email.max'                => '输入的邮箱地址太长，应小于128字节',
            'email.unique'             => '邮箱地址已存在',
            'name.required'            => '请输入昵称',
            'name.username'            => '昵称只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.display_length'      => '昵称长度不合法',
            'name.unique'              => '昵称已经被其他用户所使用',
            'name.not_in'              => '系统保留昵称，禁止使用',
            'verifiable_code.required' => '请输入验证码',
            'verifiable_type.required' => '非法请求',
            'verifiable_type.in'       => '非法的请求参数',
        ];
    }
}
