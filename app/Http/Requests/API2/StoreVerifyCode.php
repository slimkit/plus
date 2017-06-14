<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;

class StoreVerifyCode extends FormRequest
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
    public function rules(): array
    {
        return [
            'phone' => 'required_without:email|cn_phone|exists:users,phone',
            'email' => 'required_without:phone|email|max:128|exists:users,email',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'phone.required_without' => '请求的手机号码不能为空',
            'phone.cn_phone' => '请求的手机号码必须是大陆地区合法手机号码',
            'phone.exists' => '请求的用户不存在',

            'email.required_without' => '请求的邮箱地址不能为空',
            'email.email'  => '请求的邮箱地址格式无效',
            'email.max'    => '请求的邮箱地址太长，应小于128字节',
            'email.exists' => '请求的用户不存在',
        ];
    }
}
