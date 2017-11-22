<?php

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

        $configuration->save($config);

        return response()->json(['message' => '更新成功'], 201);
    }

    private function initWalletSwitch(Configuration $configuration)
    {
        $config = $configuration->getConfiguration();

        $config->set('wallet.cash.status', true);
        $config->set('wallet.recharge.status', true);

        $configuration->save($config);

        return $config['wallet'];
    }
}
