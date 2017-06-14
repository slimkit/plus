<?php

namespace Zhiyi\Plus\Http\Requests\API2;

class CreateRegisterVerifyCodeRequest extends StoreVerifyCode
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'phone' => 'required_without:email|cn_phone|unique:users,phone',
            'email' => 'required_without:phone|email|max:128|unique:users,email',
        ]);
    }

    /**
     * Get the validation messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return array_merge(parent::messages(), [
            'phone.unique' => '手机号码已经被使用，不能发送验证码',
            'email.unique' => '邮箱地址已经被使用，不能发送验证码',
        ]);
    }
}
