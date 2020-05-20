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

namespace SlimKit\PlusAroundAmap\Admin\Controllers;

use SlimKit\PlusAroundAmap\Admin\Requests\StoreAmapRequest;
use Zhiyi\Plus\Support\Configuration;

class HomeController
{
    public function index()
    {
        $around = [];
        $around['sig'] = config('around-amap.amap-sig');
        $around['key'] = config('around-amap.amap-key');
        $around['tableid'] = config('around-amap.amap-tableid');
        $around['jssdk'] = config('around-amap.amap-jssdk');

        return view('around-amap::admin', $around);
    }

    public function save(StoreAmapRequest $request, Configuration $config)
    {
        $data = $request->only('amap-sig', 'amap-tableid', 'amap-key', 'amap-jssdk');
        $config->set('around-amap', $data);

        return redirect('around-amap/admin')->with('status', '保存成功！ :)');
    }
}
