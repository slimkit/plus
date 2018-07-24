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

namespace Zhiyi\Plus\Repository;

use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class CurrencyConfig
{
    /**
     * Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create the cash type respositorie.
     *
     * @author BS <414606094@qq.com>
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the Currency recharge ratio.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function get(): array
    {
        if ($this->cache->has($this->cacheKey())) {
            return $this->cache->get($this->cacheKey());
        }

        $ratio = CommonConfig::firstOrCreate(
            ['name' => 'currency:recharge-ratio', 'namespace' => 'currency'],
            ['value' => 1]
        );

        $options = CommonConfig::firstOrCreate(
            ['name' => 'currency:recharge-option', 'namespace' => 'currency'],
            ['value' => '100, 500, 1000, 2000, 5000, 10000']
        );

        $max = CommonConfig::firstOrCreate(
            ['name' => 'currency:recharge-max', 'namespace' => 'currency'],
            ['value' => 10000000]
        );

        $min = CommonConfig::firstOrCreate(
            ['name' => 'currency:recharge-min', 'namespace' => 'currency'],
            ['value' => 100]
        );

        $cash_max = CommonConfig::firstOrCreate(
            ['name' => 'currency:cash-max', 'namespace' => 'currency'],
            ['value' => 10000000]
        );

        $cash_min = CommonConfig::firstOrCreate(
            ['name' => 'currency:cash-min', 'namespace' => 'currency'],
            ['value' => 100]
        );

        $datas = [
            'recharge-ratio' => (int) $ratio->value,
            'recharge-options' => (string) $options->value,
            'recharge-max' => (int) $max->value,
            'recharge-min' => (int) $min->value,
            'cash-max' => (int) $cash_max->value,
            'cash-min' => (int) $cash_min->value,
        ];

        $this->cache->forever($this->cacheKey(), $datas);

        return $datas;
    }

    /**
     * Get the config cache key.
     *
     * @return string
     * @author BS <414606094@qq.com>
     */
    public function cacheKey(): string
    {
        return 'currency:config';
    }

    /**
     * Flush all cache.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function flush()
    {
        $keys = [
            $this->cacheKey(),
            'bootstrappers',
        ];

        foreach ($keys as $key) {
            $this->cache->forget($key);
        }
    }
}
