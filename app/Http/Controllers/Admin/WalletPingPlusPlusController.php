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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Contracts\Routing\ResponseFactory as ContractResponse;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\setting;

class WalletPingPlusPlusController extends Controller
{
    /**
     * Get the Ping++ config.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ContractResponse $response)
    {
        return $response
            ->json(setting('wallet', 'ping++', []))
            ->setStatusCode(200);
    }

    /**
     * Update Ping++ config.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ContractResponse $response)
    {
        $this->validate($request, $this->rules(), $this->validateErrorMessages());

        setting('wallet')->set('ping++', $request->only(['app_id', 'secret_key', 'public_key', 'private_key']));

        return $response
            ->json(['message' => ['更新成功!']])
            ->setStatusCode(201);
    }

    /**
     * Get valodate rule.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function rules(): array
    {
        return [
            'app_id' => 'required',
            'secret_key' => 'required',
            'public_key' => 'required',
            'private_key' => 'required',
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
            'app_id.required' => '请输入应用 ID',
            'secret_key.required' => '请输入 Secret Key',
            'public_key.required' => '请输入 Ping++ 公钥',
            'private_key.required' => '请输入商户私钥',
        ];
    }
}
