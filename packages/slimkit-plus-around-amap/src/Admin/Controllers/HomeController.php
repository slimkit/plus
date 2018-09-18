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

namespace SlimKit\PlusAroundAmap\Admin\Controllers;

use Zhiyi\Plus\Support\Configuration;
use SlimKit\PlusAroundAmap\Admin\Requests\StoreAmapRequest;

class HomeController
{
    public function index(Configuration $config)
    {
        $conf = $config->getConfigurationBase();
        $around = [];

        $around['sig'] = array_get($conf, 'around-amap.amap-sig');
        $around['key'] = array_get($conf, 'around-amap.amap-key');
        $around['tableid'] = array_get($conf, 'around-amap.amap-tableid');
        $around['jssdk'] = array_get($conf, 'around-amap.amap-jssdk');

        return view('around-amap::admin', $around);
    }

    public function save(StoreAmapRequest $request, Configuration $config)
    {
        $data = $request->only('amap-sig', 'amap-tableid', 'amap-key', 'amap-jssdk');
        $config->set('around-amap', $data);

        return redirect('around-amap/admin')->with('status', '保存成功！ :)');
    }
}
