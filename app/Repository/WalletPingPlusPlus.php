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
use Illuminate\Contracts\Cache\Repository as ContractsCacheRepository;

class WalletPingPlusPlus
{
    /**
     * Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create the repository instance.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ContractsCacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the Ping++ configuration.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(): array
    {
        if ($this->cache->has($this->cacheKey())) {
            return $this->cache->get($this->cacheKey());
        }

        $model = CommonConfig::firstOrCreate(
            ['name' => 'ping++', 'namespace' => 'wallet'],
            ['value' => '[]']
        );

        $this->cache->forever(
            $this->cacheKey(),
            $config = $this->resolveConfig(json_decode($model->value, true))
        );

        return $config;
    }

    /**
     * Save the Ping++ configuration.
     *
     * @param array $config
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(array $config)
    {
        CommonConfig::updateOrCreate(
            ['name' => 'ping++', 'namespace' => 'wallet'],
            ['value' => json_encode($config = $this->resolveConfig($config))]
        );

        $this->flush();
        $this->cache->forever($this->cacheKey(), $config);
    }

    /**
     * Get the config cache key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function cacheKey(): string
    {
        return 'wallet:pay-ping++';
    }

    /**
     * Flush all cache.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function flush()
    {
        $keys = [
            $this->cacheKey(),
        ];

        foreach ($keys as $key) {
            $this->cache->forget($key);
        }
    }

    /**
     * Resolve config.
     *
     * @param array $config
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveConfig(array $config = []): array
    {
        return array_merge([
            'app_id' => null,
            'secret_key' => null,
            'public_key' => null,
            'private_key' => null,
        ], $config);
    }
}
