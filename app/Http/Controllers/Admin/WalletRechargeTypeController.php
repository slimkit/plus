<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;
use Zhiyi\Plus\Repository\WalletRechargeType as RechargeTypeRepository;

class WalletRechargeTypeController extends Controller
{
    /**
     * Get support types.
     *
     * @param \Zhiyi\Plus\Repository\WalletRechargeType $repository
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(RechargeTypeRepository $repository, ContractResponse $response)
    {
        return $response
            ->json(['support' => $this->getSupportTypes(), 'types' => $repository->get()])
            ->setStatusCode(200);
    }

    /**
     * Update support types.
     *
     * @param \Zhiyi\Plus\Repository\WalletRechargeType $repository
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(RechargeTypeRepository $repository, Request $request, ContractResponse $response)
    {
        $this->validate($request, $this->rules(), $this->validateErrorMessages());

        $repository->store(
            $request->input('types')
        );

        return $response
            ->json(['message' => ['更新成功']])
            ->setStatusCode(201);
    }

    /**
     * Get support types.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getSupportTypes(): array
    {
        return [
            // Apple
            'applepay_upacp' => 'Apple Pay (仅对 iOS 有效)',

            // Alipay
            'alipay' => '支付宝 APP 支付',
            'alipay_wap' => '支付宝手机网页支付',
            'alipay_pc_direct' => '支付宝电脑网站支付',
            'alipay_qr' => '支付宝扫码支付',

            // WeChat
            'wx' => '微信 APP 支付',
            'wx_wap' => '微信 WAP 支付',
        ];
    }

    /**
     * Get validate rules.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function rules(): array
    {
        return [
            'types' => 'array|in:'.implode(',', array_keys($this->getSupportTypes())),
        ];
    }

    /**
     * Get validate error messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateErrorMessages(): array
    {
        return [
            'types.array' => '发送类型错误',
            'types.in' => '选择类型存在非法选择，目前仅支持：'.implode('、', array_values($this->getSupportTypes())),
        ];
    }
}
