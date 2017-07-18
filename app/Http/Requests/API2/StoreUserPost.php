<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

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
            'phone' => 'required_without:email|cn_phone|unique:users,phone',
            'email' => 'required_without:phone|email|max:128|unique:users,email',
            'name' => 'required|username|display_length:2,12|unique:users,name',
            'password' => 'required|string',
            'verifiable_code' => 'required',
            'verifiable_type' => 'required|string|in:mail,sms',
        ];
    }

    /**
     * Get rule messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'phone.required_without' => '请输入用户手机号码',
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'email.required_without' => '请输入用户邮箱地址',
            'email.email'  => '请输入有效的邮箱地址',
            'email.max'    => '输入的邮箱地址太长，应小于128字节',
            'email.unique' => '邮箱地址已存在',
            'name.required' => '请输入用户名',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.display_length' => '用户名长度不合法',
            'name.unique' => '用户名已经被其他用户所使用',
            'password.required' => '请输入密码',
            'verifiable_code.required' => '请输入验证码',
            'verifiable_type.required' => '非法请求',
            'verifiable_type.in' => '非法的请求参数',
        ];
    }
}
