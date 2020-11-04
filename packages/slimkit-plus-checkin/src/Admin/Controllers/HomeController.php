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

namespace SlimKit\PlusCheckIn\Admin\Controllers;

use SlimKit\PlusCheckIn\Admin\Requests\StoreConfig as StoreConfigRequest;
use function Zhiyi\Plus\setting;

class HomeController
{
    /**
     * 签到后台入口.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index()
    {
        return view('plus-checkin::admin', [
            'switch' => setting('checkin', 'switch', true),
            'balance' => setting('checkin', 'attach-balance', 1),
        ]);
    }

    /**
     * Store checkin config.
     *
     * @param \SlimKit\PlusCheckIn\Admin\Requests\StoreConfig $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreConfigRequest $request)
    {
        setting('checkin')->set([
            'switch' => (bool) $request->input('switch'),
            'attach-balance' => (int) $request->input('balance'),
        ]);

        return redirect()->back()->with('message', trans('plus-checkin::messages.success'));
    }
}
