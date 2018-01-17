<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGitHubAccessRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'username.required' => '请输入 GotHub 用户名',
            'username.string' => 'GitHub 用户名必须是字符串',
            'password.required' => '请输入 GitHub 密码',
            'password.string' => '密码必须是字符串',
        ];
    }
}
