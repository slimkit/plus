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
            'phone' => 'required|cn_phone|unique:users,phone',
            'name' => 'required|username|min:2|max:12|unique:users,name',
            'password' => 'required',
            'verify_code' => 'required',
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
            'phone.required' => '请输入用户手机号码',
            'phone.cn_phone' => '请输入大陆地区合法手机号码',
            'phone.unique' => '手机号码已经存在',
            'name.required' => '请输入用户名',
            'name.username' => '用户名只能以非特殊字符和数字开头，不能包含特殊字符',
            'name.min' => '用户名最少输入两个字',
            'name.max' => '用户名最多输入十二个字',
            'name.unique' => '用户名已经被其他用户所使用',
            'password.required' => '请输入密码',
            'verify_code.required' => '请输入验证码',
        ];
    }
}
