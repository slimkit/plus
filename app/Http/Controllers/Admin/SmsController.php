<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Models\VerificationCode;
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
    public function show(Request $request, ResponseFactory $response, VerificationCode $model)
    {
        $state = $request->query('state');
        $phone = $request->query('phone');
        $limit = $request->query('limit', 20);

        $data = $model->withTrashed()
            ->when(boolval($state), function ($query) use ($state) {
                return $query->where('state', $state);
            })
            ->when(boolval($phone), function ($query) use ($phone) {
                return $query->where('account', 'like', sprintf('%%%s%%', $phone));
            })
            ->orderBy('id', 'desc')
            ->simplePaginate($limit);

        return $response->json($data, 200);
    }

    /**
     * Get SMS driver configuration information.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param string $driver
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showOption(Repository $config, ResponseFactory $response, string $driver)
    {
        if (! in_array($driver, array_keys($config->get('sms.gateways')))) {
            return $response->json(['message' => ['当前驱动不存在于系统中']], 422);
        }

        $data = $config->get(sprintf('sms.gateways.%s', $driver), []);
        $data['verify_template_id'] = $config->get(sprintf('sms.channels.code.alidayu.template'));

        return $response->json($data, 200);
    }

    /**
     * Update Ali SMS configuration information.
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
        $config = $store->getConfiguration();
        $config->set(
            'sms.gateways.alidayu',
            $request->only(['app_key', 'app_secret', 'sign_name'])
        );
        $config->set(
            'sms.channels.code.alidayu.template',
            $request->input('verify_template_id')
        );
        $store->save($config);

        return $response->json(['message' => ['更新成功']], 201);
    }
}
