<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

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
        $query = app(VerifyCode::class)
            ->newQuery()
            ->withTrashed();

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

    /**
     * 获取短信驱动配置信息.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param string $driver
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showOption(Repository $config, ResponseFactory $response, string $driver)
    {
        if (! in_array($driver, array_keys($config->get('sms.driver')))) {
            return $response->json(['message' => ['当前驱动不存在于系统中']], 422);
        }

        $data = $config->get(sprintf('sms.connections.%s', $driver), []);

        return $response->json($data, 200);
    }

    /**
     * 更新阿里短信配置信息.
     *
     * @param Repository $config
     * @param Configuration $store
     * @param Request $request
     * @param ResponseFactory $response
     * @param string $driver
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateAlidayuOption(Repository $config, Configuration $store, Request $request, ResponseFactory $response)
    {
        $store->set(
            'sms.connections.alidayu',
            $request->only(['app_key', 'app_secret', 'sign_name', 'verify_template_id'])
        );

        return $response->json(['message' => ['更新成功']], 201);
    }
}
