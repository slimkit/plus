<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTag extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required_without_all:category,weight|max:10|unique:tags',
            'category' => [
                'required_without_all:name,weight',
                Rule::exists('tag_categories', 'id'),
            ],
            'weight' => 'required_without_all:name,category|numeric|min:0',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'name.required_without_all' => '没有进行任何修改1',
            'name.max' => '标签名称过长',
            'name.unique' => '标签已经存在',
            'category.required_without_all' => '没有进行任何修改2',
            'category.exists' => '标签分类不存在',
            'weight.required_without_all' => '没有进行任何修改3',
            'weight.numeric' => '权重值必须为数字',
            'weight.min' => '权重值不能小于0',
        ];
    }
}
