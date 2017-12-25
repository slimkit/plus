<?php

namespace SlimKit\PlusQuestion\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopic extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'sort' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '话题名称不能为空',
            'name.max' => '话题名称不能超过50个字',
            'description.required' => '话题描述不能为空',
            'sort.integer' => '排序必须是整数',
        ];
    }
}
