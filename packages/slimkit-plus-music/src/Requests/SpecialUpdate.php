<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SpecialUpdate extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'required|max:20',
            'amount' => 'numeric|min:0.01',
            'paid_node' => 'array',
            'sort' => 'min:0',
            'intro' => 'required|max:50'
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
            'title.max' => '=标题超长，最多20个字',
            'title.required' => '标题必填',
            'sort.min' => '权重必须大于0',
            'intro.required' => '简介必填',
            'intro.max' => '简介最多50字'
        ];
    }
}
