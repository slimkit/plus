<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use function Zhiyi\Plus\username;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoginPost extends FormRequest
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
    public function rules(): array
    {
        $username = username($this->input('login', ''));
        $loginValidateRuleMap = ['required', 'string'];

        if ($username === 'phone') {
            $loginValidateRuleMap[] = 'cn_phone';
        }
        $loginValidateRuleMap[] = 'exists:users,'.$username;

        return [
            'login' => $loginValidateRuleMap,
            'password' => ['required'],
        ];
    }

    /**
     * Get rule messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'login.required' => '账户不能为空',
            'login.string' => '输入的账户非法',
            'login.cn_phone' => '请输入符合大陆地区合法手机号码',
            'login.exists' => '登录的账户不存在',
            'password.required' => '密码不能为空',
        ];
    }
}
