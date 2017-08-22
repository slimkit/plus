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
            'name' => 'required_without:category|required|max:10|unique:tags',
            'category' => [
                'required_without:name',
                Rule::exists('tag_categories', 'id'),
            ],
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
            'name.required_without' => '标签名称和分类至少提交一个',
            'name.max' => '标签名称过长',
            'name.unique' => '标签已经存在',
            'category.required_without' => '标签名称和分类至少提交一个',
            'category.exists' => '标签分类不存在',
        ];
    }
}
