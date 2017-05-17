<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Validation\Rule;
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
            'phone' => [
                'required',
                'cn_phone',
                'unique:users,phone',
                // Rule::unique('users')->where(function ($query) {
                //     $query->where('')
                // }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '请求的手机号码不能为空',
            'phone.cn_phone' => '请求的手机号码必须是大陆地区合法手机号码',
            'phone.unique' => '手机号码已经被使用，不能发送验证码',
        ];
    }
}
