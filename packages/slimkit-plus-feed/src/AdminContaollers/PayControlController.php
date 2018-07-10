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
            $items[$key] = $value;
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
                $paycontrol[$key] = $item;
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
