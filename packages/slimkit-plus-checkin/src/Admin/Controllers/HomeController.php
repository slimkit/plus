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

namespace SlimKit\PlusCheckIn\Admin\Controllers;

use Zhiyi\Plus\Support\Configuration;
use SlimKit\PlusCheckIn\Admin\Requests\StoreConfig as StoreConfigRequest;

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
            'switch' => config('checkin.open'),
            'balance' => config('checkin.attach_balance'),
        ]);
    }

    /**
     * Store checkin config.
     *
     * @param \SlimKit\PlusCheckIn\Admin\Requests\StoreConfig $request
     * @param \Zhiyi\Plus\Support\Configuration $config
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreConfigRequest $request, Configuration $config)
    {
        $switch = (bool) $request->input('switch');
        $balance = (int) $request->input('balance');

        $config->set([
            'checkin.open' => $switch,
            'checkin.attach_balance' => $balance,
        ]);

        return redirect()->back()->with('message', trans('plus-checkin::messages.success'));
    }
}
