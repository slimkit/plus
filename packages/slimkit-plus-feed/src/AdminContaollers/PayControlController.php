<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class PayControlController extends Controller
{
    protected $config;

    // 获取当前付费配置
    public function getCurrentStatus(ConfigRepository $config)
    {
        $items = $config->get('feed.items') ?? [100, 500, 1000];
        $textLength = $config->get('feed.limit') ?? 50;

        foreach ($items as $key => $value) {
            $items[$key] = $value / 100;
        }

        return response()->json([
            'open' => $config->get('feed.paycontrol') ?? false,
            'payItems' => implode($items, ',') ?? '',
            'textLength' => $textLength,
        ])
        ->setStatusCode(200);
    }

    /**
     * 更新动态付费状态
     */
    public function updateStatus(Request $request, Configuration $config)
    {
        $open = $request->input('open');
        $limit = intval($request->input('textLength'));
        $paycontrol = $request->input('payItems');
        if ($paycontrol) {
            $paycontrol = explode(',', $paycontrol);
            foreach ($paycontrol as $key => $item) {
                $paycontrol[$key] = $item * 100;
            }
        }

        $configuration = $config->getConfiguration();
        if (isset($open)) {
            $configuration->set('feed.paycontrol', $open);
        }
        if ($limit) {
            $configuration->set('feed.limit', $limit);
        }
        if ($paycontrol) {
            $configuration->set('feed.items', $paycontrol);
        }
        $config->save($configuration);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }
}
