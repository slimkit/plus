<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Support\Configuration;

class SmsController extends Controller
{
    /**
     * Show SMS logs.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactory $response)
    {
        $state = $request->query('state');
        $phone = $request->query('phone');
        $limit = $request->query('limit', 20);
        $page = $request->query('page');
        $query = app(VerifyCode::class)->newQuery();

        if ($state !== null) {
            $query->where('state', $state);
        }

        if ($phone) {
            $query->where('account', 'like', sprintf('%%%s%%', $phone));
        }

        $query->latest();

        $data = $query->simplePaginate($limit);

        return $response->json($data, 200);
    }

    /**
     * Show driver.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showDriver(Repository $config, ResponseFactory $response)
    {
        $default = $config->get('sms.default');
        $driver = $config->get('sms.driver');

        return $response->json(['default' => $default, 'driver' => $driver], 200);
    }

    /**
     * 更新驱动程序选择.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Zhiyi\Plus\Support\Configuration $store
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateDriver(Request $request, ResponseFactory $response, Repository $config, Configuration $store)
    {
        $default = $request->input('default');

        if (! in_array($default, array_keys($config->get('sms.driver')))) {
            return $response->json(['message' => ['选择的驱动类型不在系统当中']], 422);
        }

        $store->set('sms.default', $default);

        return $response->json(['message' => ['设置成功']], 201);
    }
}
