<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Zhiyi\Plus\Repository\UserWalletCashType;

class StoreUserWallerCashPost extends FormRequest
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
    public function rules(UserWalletCashType $repository)
    {
        return [
            'value' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$this->user()->wallet->balance,
            ],
            'type' => [
                'required',
                Rule::in($repository->get()),
            ],
            'account' => ['required']
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
            'value.required' => '请输入提现金额',
            'value.numeric' => '发送的数据错误',
            'value.min' => '提现最低输入 1',
            'value.max' => '提现金额超出账户余额',
            'type.required' => '请选择提现方式',
            'type.in' => '你选择的提现方式不支持',
            'account.required' => '请输入你的提现账户',
        ];
    }
}
