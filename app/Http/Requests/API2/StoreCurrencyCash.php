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

use Illuminate\Foundation\Http\FormRequest;
use function Zhiyi\Plus\setting;

class StoreCurrencyCash extends FormRequest
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
     * @author BS <414606094@qq.com>
     */
    public function rules()
    {
        $settings = setting('currency', 'settings', [
            'cash-max' => 10000000,
            'cash-min' => 100,
        ]);
        $currency = $this->user()->currency()->firstOrCreate(['type' => 1], ['sum' => 0]);

        return [
            'amount' => [
                'required',
                'integer',
                sprintf('min:%d', $settings['cash-min']),
                sprintf('max:%d', min($currency->sum, $settings['cash-max'])),
            ],
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
        $settings = setting('currency', 'settings', [
            'cash-min' => 100,
        ]);

        return [
            'amount.required' => '请选择需要提取的积分',
            'amount.min' => sprintf('最低提现金额为：“%d”', $settings['cash-min']),
            'amount.max' => '账户积分余额不足或超出最大提现限制',
        ];
    }
}
