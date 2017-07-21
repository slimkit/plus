<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Validation\Rule;
use Zhiyi\Plus\Models\Certification;
use Illuminate\Foundation\Http\FormRequest;

class UserCertificationPost extends FormRequest
{
    /**
     * authorization check.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        // 检查认证用户所在用户组是否有发送分享权限.
        return true;
    }

    public function rules()
    {
        $rules = [
            'certification' => [
                'required',
                'exists:certifications,id',
            ],
            'id' => 'bail|required|max:50', // 验证证件ID
            'contact' => 'bail|required', // 验证联系方式
            'desc' => 'bail|required|string|max:250', // 验证简介
            'file' => [
                'bail',
                'required',
                'integer',
                Rule::exists('file_withs', 'id')->where(function ($query) {
                    $query->where('channel', null);
                    $query->where('raw', null);
                }),
            ], // 验证证件照片
            'tips' => 'max:100', // 验证备注
        ];

        return $this->input('certification') == 2 // 企业认证
            ? array_merge($rules, [
                'company_name' => 'bail|required|string|min:2|max:50',
                'contact_name' => 'bail|required|string|max:20',
            ])
            : array_merge($rules, [
                'name' => 'bail|required|string|min:2|max:50', // 验证名称
            ]);
    }

    public function messages()
    {
        $messages = [
            'certification.required' => '认证类型未提供',
            'certification.exists' => '认证类型不存在',
            'id.required' => '证件号未提供',
            'contact.required' => '联系方式未提供',
            'desc.required' => '认证描述未提供',
            'desc.max' => '认证描述长度最大250',
            'file.required' => '证件照片未提供',
            'file.exists' => '文件不存在或已被使用',
            'tips' => '备注最长100',
        ];

        return $this->input('certification') == 2
            ? array_merge($messages, [
                'company_name.required' => '企业名称未提供',
                'company_name.min' => '企业名称太短',
                'company_name.max' => '企业名称最长100',
                'contact_name.required' => '联系人未提供',
                'contact_name.max' => '联系人名称长度最大20',
            ])
            : array_merge($messages, [
                'name.required' => '姓名未提供',
                'name.min' => '姓名太短',
                'name.max' => '姓名太长',
            ]);
    }
}
