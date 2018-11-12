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
        $bootstrappers['registerSettings'] = config('registerSettings', null);

        $bootstrappers['wallet:cash'] = ['open' => config('wallet.cash.status', true)];
        $bootstrappers['wallet:recharge'] = ['open' => config('wallet.recharge.status', true)];
        $bootstrappers['wallet:transform'] = ['open' => config('wallet.transform.status', true)];

        $bootstrappers['currency:cash'] = ['open' => config('currency.cash.status', true)];
        $bootstrappers['currency:recharge'] = ['open' => config('currency.recharge.status', true), 'IAP_only' => config('currency.recharge.IAP.only', true)];

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
