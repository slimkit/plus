<?php

namespace Zhiyi\Plus\Http\Requests\API2;

use Illuminate\Foundation\Http\FormRequest;
use Zhiyi\Plus\Repository\WalletRechargeType;

class StoreWalletRecharge extends FormRequest
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
     * @param \Zhiyi\Plus\Repository\WalletRechargeType $repository
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(WalletRechargeType $repository)
    {
        return [
            'type' => 'required|in:'.implode(',', $repository->get()),
            'amount' => 'required|min:1|max:1000000',
        ];
    }

    /**
     * Get the valodation error message that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages()
    {
        return [
            'type.required' => '请选择充值方式',
            'type.in' => '选择的充值方式错误',
            'amount.required' => '请选择需要充值金额',
            'amount.min' => '充值金额不合法',
            'amount.max' => '充值金额超出最大充值限制',
        ];
    }
}
