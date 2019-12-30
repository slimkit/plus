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

class StoreTransform extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function rules(): array
    {
        $currency = $this->user()->newWallet()->firstOrCreate([], ['balance' => 0, 'total_income' => 0, 'total_expenses' => 0]);
        $min = setting('currency', 'settings', ['recharge-min' => 100])['recharge-min'];

        return [
            'amount' => [
                'required',
                'integer',
                sprintf('min:%d', $min),
                sprintf('max:%d', $currency->balance),
            ],
        ];
    }

    /**
     * Get the valodation error message that apply to the request.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function messages(): array
    {
        return [
            'amount.required' => '请选择需要转换的余额',
            'amount.min' => '余额参数不合法',
            'amount.max' => '账户余额不足',
        ];
    }
}
