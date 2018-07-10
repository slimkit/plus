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
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $query = $model->withTrashed()
            ->when(is_numeric($state) && $state >= 0, function ($query) use ($state) {
                return $query->where('state', $state);
            })
            ->when(boolval($phone), function ($query) use ($phone) {
                return $query->where('account', 'like', sprintf('%%%s%%', $phone));
            });

        $total = $query->count('id');
        $items = $query->orderBy('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $response->json($items, 200, ['x-sms-total' => $total]);
    }

    /**
     * 获取短信所有配置网关.
     *
     * @param Repository $config
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showGateway(Repository $config)
    {
        $data = [];
        $data['gateways'] = array_keys($config->get('sms.gateways'));
        $data['allowed_gateways'] = $config->get('sms.default.allowed_gateways') ?: [];
        $data['default_gateways'] = $config->get('sms.default.gateways') ?: [];

        return response($data, 200);
    }

    /**
     * 更新允许的网关.
     *
     * @param Request $request
     * @param Repository $config
     * @param Configuration $store
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateGateway(Request $request, Repository $config, Configuration $store)
    {
        $gateways = $request->input('gateways');
        $type = $request->input('type');

        if (! is_array($gateways) || ! $type) {
            return response(['message' => ['数据格式错误或类型参数错误']], 422);
        }

        $config = $store->getConfiguration();

        $key = ($type === 'sms') ? 'gateways' : 'allowed_gateways';

        $config->set(sprintf('sms.default.%s', $key), $gateways);

        $store->save($config);

        return response(['message' => ['更新成功']], 201);
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
    public function updateAlidayuOption(Configuration $store, Request $request, ResponseFactory $response)
    {
        $config = $store->getConfiguration();

        $config->set(
            'sms.gateways.alidayu',
            $request->only(['app_key', 'app_secret', 'sign_name'])
        );

        $store->save($config);

        return $response->json(['message' => ['更新成功']], 201);
    }

    /**
     * Update Aliyun SMS configuration information.
     *
     * @param Repository $config
     * @param Configuration $store
     * @param Request $request
     * @param ResponseFactory $response
     * @param string $driver
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateAliyunOption(Configuration $store, Request $request)
    {
        $config = $store->getConfiguration();

        $config->set(
            'sms.gateways.aliyun',
            $request->only(['access_key_id', 'access_key_secret', 'sign_name'])
        );

        $store->save($config);

        return response()->json(['message' => ['更新成功']], 201);
    }

    /**
     * Update Yunpian SMS configuration information.
     *
     * @param Repository $config
     * @param Configuration $store
     * @param Request $request
     * @param ResponseFactory $response
     * @param string $driver
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateYunpianOption(Configuration $store, Request $request)
    {
        $config = $store->getConfiguration();

        $config->set(
            'sms.gateways.yunpian',
            $request->only(['api_key'])
        );

        $store->save($config);

        return response()->json(['message' => ['更新成功']], 201);
    }

    public function updateHuyiOption(Configuration $store, Request $request)
    {
        $config = $store->getConfiguration();

        $config->set(
            'sms.gateways.huyi',
            $request->only(['api_key', 'api_id', 'content'])
        );

        $store->save($config);

        return response()->json(['message' => ['更新成功']], 201);
    }

    /**
     * Get SMS driver Template configuration information.
     *
     * @param  Repository $config [description]
     * @return [type]             [description]
     */
    public function smsTemplate(Repository $config)
    {
        $data = [];

        $data['alidayu_template_id'] = $config->get('sms.channels.code.alidayu.template');
        $data['aliyun_template_id'] = $config->get('sms.channels.code.aliyun.template');
        $data['yunpian_template_content'] = $config->get('sms.channels.code.yunpian.content');
        $data['huyi_template_content'] = $config->get('sms.channels.code.huyi.content');

        return response()->json($data, 200);
    }

    /**
     * Update SMS driver Template configuration information.
     *
     * @param  Repository    $config
     * @param  Configuration $store
     * @param  Request       $request
     * @return [type]
     */
    public function updateTemplate(Configuration $store, Request $request)
    {
        $config = $store->getConfiguration();

        $config->set(
            'sms.channels.code.alidayu.template',
            $request->input('alidayu_template_id')
        );

        $config->set(
            'sms.channels.code.aliyun.template',
            $request->input('aliyun_template_id')
        );

        $config->set(
            'sms.channels.code.yunpian.content',
            $request->input('yunpian_template_content')
        );

        $config->set(
            'sms.channels.code.huyi.content',
            $request->input('huyi_template_content')
        );

        $store->save($config);

        return response()->json(['message' => ['更新成功']], 201);
    }
}
