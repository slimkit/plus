<?php

namespace Zhiyi\Plus\EaseMobIm;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckGroup extends FormRequest
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
            'groupname' => 'required|string',
            'group_face' => [
                'integer',
                Rule::exists('file_withs', 'id')->where(function ($query) {
                    $query->where('channel', null);
                    $query->where('raw', null);
                }),
            ],
            'desc' => 'required|string',
            'numbers' => 'array',
            'public' => 'boolean|nullable',
            'members_only' => 'nullable',
            'allowinvites' => 'boolean|nullable',
        ];
    }

    /**
     * return validation messages.
     *
     */
    public function messages()
    {
        return [
            'groupname.required' => '群组名称不能为空',
            'desc.required' => '群组简介不能为空',
            'group_face.exists' => '文件不存在或已经被使用',
        ];
    }
}
