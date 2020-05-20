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
use Illuminate\Validation\Rule;
use Zhiyi\Plus\Packages\Wallet\Wallet;

class Transfer extends FormRequest
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
    public function rules()
    {
        $wallet = new Wallet($this->user());

        return [
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$wallet->getWalletModel()->balance,
            ],
            'user' => ['required'],
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
            'amount.required' => '请输入转账金额',
            'amount.numeric' => '发送的数据错误',
            'amount.min' => '转账金额不能小于0.01元',
            'amount.max' => '转账金额超出账户余额',
            'user.required' => '请填写转账用户',
        ];
    }
}
