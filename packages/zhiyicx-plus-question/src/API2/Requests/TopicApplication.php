<?php

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
