<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoginPost extends FormRequest
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
            'phone' => 'required|cn_phone|exists:users,phone',
            'password' => 'required',
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
            'phone.required' => '手机号不能为空',
            'phone.cn_phone' => '请输入中国大陆合法手机号码',
            'phone.exists' => '登录的用户不存在',
            'password.required' => '密码不能为空',
        ];
    }
}
