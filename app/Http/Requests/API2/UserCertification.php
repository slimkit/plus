<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Zhiyi\Plus\Models\Certification;
use Illuminate\Foundation\Http\FormRequest;

class UserCertification extends FormRequest
{
    /**
     * authorization check.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 检查认证用户所在用户组是否有发送分享权限.
        return true;
    }

    public function rules(): array
    {
        if (strtolower($this->getMethod()) === 'patch') {
            return $this->updateRules();
        }

        $baseRules = [
            'type' => ['bail', 'required', 'string', 'in:user,org'],
            'name' => ['bail', 'required', 'string'],
            'phone' => ['bail', 'required', 'string', 'cn_phone'],
            'number' =>['bail', 'required', 'string'],
            'desc' => ['bail', 'required', 'string'],
            'files' => 'bail|required|array',
            'files.*' => 'bail|required_with:files|integer|exists:file_withs,id,channel,NULL,raw,NULL',
        ];

        if ($this->input('type') === 'org') {
            return array_merge($baseRules, [
                'org_name' => ['bail', 'required', 'string'],
                'org_address' => ['bail', 'required', 'string'],
            ]);
        }

        return $baseRules;
    }

    public function updateRules(): array
    {
        $baseRules = [
            'type' => ['bail', 'nullable', 'string', 'in:user,org'],
            'name' => ['bail', 'nullable', 'string'],
            'phone' => ['bail', 'nullable', 'string', 'cn_phone'],
            'number' =>['bail', 'nullable', 'string'],
            'desc' => ['bail', 'nullable', 'string'],
            'files' => 'bail|nullable|array',
            'files.*' => 'bail|required_with:files|integer|exists:file_withs,id',
        ];

        if ($this->input('type') === 'org') {
            return array_merge($baseRules, [
                'org_name' => ['bail', 'nullable', 'string'],
                'org_address' => ['bail', 'nullable', 'string'],
            ]);
        }

        return $baseRules;
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
            'files.required' => '证件照片未提供',
            'files.exists' => '文件不存在或已被使用',
        ];

        return $this->input('certification') === 'enterprise_certification'
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
