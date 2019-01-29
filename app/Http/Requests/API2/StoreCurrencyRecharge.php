<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Requests\API2;

use Zhiyi\Plus\Repository\CurrencyConfig;
use Illuminate\Foundation\Http\FormRequest;
use Zhiyi\Plus\Repository\WalletRechargeType;

class StoreCurrencyRecharge extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && config('currency.recharge.status', true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Zhiyi\Plus\Repository\WalletRechargeType $repository
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function rules(WalletRechargeType $repository, CurrencyConfig $config)
    {
        return [
            'type' => 'required|in:'.implode(',', $repository->get()),
            'amount' => 'required|int|min:100|max:'.$config->get()['recharge-max'],
            'extra' => 'array',
        ];
    }

    /**
     * Get the valodation error message that apply to the request.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function messages()
    {
        return [
            'type.required' => '请选择充值方式',
            'type.in' => '选择的充值方式错误',
            'amount.required' => '请选择需要充值金额',
            'amount.min' => '充值金额不合法',
            'amount.max' => '充值金额超出最大充值限制',
            'extra.array' => '参数错误',
        ];
    }
}
