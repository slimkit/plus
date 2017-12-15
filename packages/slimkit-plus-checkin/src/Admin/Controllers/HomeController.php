<?php

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
