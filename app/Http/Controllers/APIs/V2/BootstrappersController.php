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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\GoldType;
use function Zhiyi\Plus\setting;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\CurrencyType;
use Zhiyi\Plus\Models\AdvertisingSpace;
use Illuminate\Contracts\Support\Arrayable;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;

class BootstrappersController extends Controller
{
    /**
     * Gets the list of initiator configurations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BootstrapAPIsEventer $events, AdvertisingSpace $space, GoldType $goldType): JsonResponse
    {
        $bootstrappers = [
            'server:version' => app()->version(),
        ];
        foreach (CommonConfig::byNamespace('common')->get() as $bootstrapper) {
            $bootstrappers[$bootstrapper->name] = $this->formatValue($bootstrapper->value);
        }

        $bootstrappers['ad'] = $space->where('space', 'boot')->with(['advertising' => function ($query) {
            $query->orderBy('sort', 'asc');
        }])->first()->advertising ?? [];

        $bootstrappers['site'] = [
            'about_url' => setting('site', 'about-url'),
            'anonymous' => setting('user', 'anonymous', []),
            'client_email' => setting('site', 'client-email'),
            'gold' => [
                'status' => setting('site', 'gold-switch'),
            ],
            'reserved_nickname' => setting('user', 'keep-username'),
            'reward' => setting('site', 'reward', []),
            'user_invite_template' => setting('user', 'invite-template'),
        ];
        $bootstrappers['registerSettings'] = setting('user', 'register-setting', [
            'showTerms' => true,
            'method' => 'all',
            'content' => '# 服务条款及隐私政策',
            'fixed' => 'need',
            'type' => 'all',
        ]);

        $bootstrappers['wallet:cash'] = ['open' => config('wallet.cash.status', true)];
        $bootstrappers['wallet:recharge'] = ['open' => config('wallet.recharge.status', true)];
        $bootstrappers['wallet:transform'] = ['open' => config('wallet.transform.status', true)];

        $bootstrappers['currency'] = [
            'IAP_only' => config('currency.recharge.IAP.only', true),
            'cash' => setting('currency', 'cash', [
                'rule' => '我是提现规则',
                'status' => true, // 提现开关
            ]),
            'recharge' => setting('currency', 'recharge', [
                'rule' => '我是积分充值规则',
                'status' => true, // 充值开关
            ]),
            'rule' => setting('currency', 'rule', '我是积分规则'),
            'settings' => setting('currency', 'settings', [
                'recharge-ratio' => 1,
                'recharge-options' => '100,500,1000,2000,5000,10000',
                'recharge-max' => 10000000,
                'recharge-min' => 100,
                'cash-max' => 10000000,
                'cash-min' => 100,
                'cash-type' => ($cash = CommonConfig::where('name', 'cash')->where('namespace', 'wallet')->first()) ? json_decode($cash->value) : [],
                'apple-IAP-rule' => config('currency.recharge.IAP.rule', ''),
                'recharge-type' => ($recharge_type = CommonConfig::where('name', 'wallet:recharge-type')->where('namespace', 'common')->first()) ? json_decode($recharge_type->value) : [],
            ]),
        ];

        $goldSetting = $goldType->where('status', 1)->select('name', 'unit')->first() ?? collect(['name' => '金币', 'unit' => '个']);
        $bootstrappers['site']['gold_name'] = $goldSetting;

        $currency = CurrencyType::where('enable', 1)->first() ?? collect(['name' => '积分', 'unit' => '']);
        $bootstrappers['site']['currency_name'] = $currency;
        config('im.helper-user') && $bootstrappers['im:helper-user'] = config('im.helper-user');
        // 每页数据量
        $bootstrappers['limit'] = config('app.data_limit');
        $bootstrappers['pay-validate-user-password'] = setting('pay', 'validate-password', false);

        return new JsonResponse($this->filterNull($events->dispatch('v2', [$bootstrappers])), JsonResponse::HTTP_OK);
    }

    /**
     * Filter null.
     * @param array $data
     * @return array
     */
    protected function filterNull(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->filterNull($value);
            } elseif ($value instanceof Arrayable) {
                $value = $this->filterNull(
                    $value->toArray()
                );
            }

            $data[$key] = $value;
        }

        return array_filter($data, function ($item) {
            if (is_array($item) || is_string($item)) {
                return ! empty($item);
            }

            return $item !== null;
        });
    }

    /**
     * 格式化数据.
     *
     * @param string $value
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function formatValue(string $value)
    {
        if (($data = json_decode($value, true)) === null) {
            return $value;
        }

        return $data;
    }
}
