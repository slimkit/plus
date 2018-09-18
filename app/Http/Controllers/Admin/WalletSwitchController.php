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
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;

class WalletSwitchController extends Controller
{
    public function show(Repository $config, Configuration $configuration)
    {
        $configs = $config->get('wallet');

        if (is_null($configs)) {
            $configs = $this->initWalletSwitch($configuration);
        }

        return response()->json($configs, 200);
    }

    public function update(Request $request, Configuration $configuration)
    {
        $switch = $request->input('switch');

        $config = $configuration->getConfiguration();

        $config->set('wallet.cash.status', $switch['cash']);
        $config->set('wallet.recharge.status', $switch['recharge']);
        $config->set('wallet.transform.status', $switch['transform']);

        $configuration->save($config);

        return response()->json(['message' => '更新成功'], 201);
    }

    private function initWalletSwitch(Configuration $configuration)
    {
        $config = $configuration->getConfiguration();

        $config->set('wallet.cash.status', true);
        $config->set('wallet.recharge.status', true);
        $config->set('wallet.transform.status', true);

        $configuration->save($config);

        return $config['wallet'];
    }
}
