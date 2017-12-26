<?php

namespace Zhiyi\Plus\EaseMobIm;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GroupMember extends FormRequest
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
            'im_group_id' => 'required|integer',
            'members' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }

    /**
     * return validation messages.
     *
     */
    public function messages()
    {
        return [
            'im_group_id.required' => '群组ID不能为空',
            'members.required' => '群成员不能为空',
            'members.exists' => '用户不存在或已经被删除',
        ];
    }
}
