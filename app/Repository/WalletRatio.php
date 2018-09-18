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
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class WalletRatio
{
    use Macroable;

    /**
     * Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create the cash type respositorie.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the ratio.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(): int
    {
        if ($this->cache->has($this->cacheKey())) {
            return (int) $this->cache->get($this->cacheKey());
        }

        $model = CommonConfig::firstOrCreate(
            ['name' => 'wallet:ratio', 'namespace' => 'common'],
            ['value' => 100]
        );

        $this->cache->forever($this->cacheKey(), $ratio = intval($model->value));

        return $ratio;
    }

    /**
     * Save or update settings.
     *
     * @param int $ratio
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(int $ratio)
    {
        CommonConfig::updateOrCreate(
            ['name' => 'wallet:ratio', 'namespace' => 'common'],
            ['value' => $ratio]
        );

        $this->flush();
        $this->cache->forever($this->cacheKey(), $ratio);
    }

    /**
     * Get the config cache key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function cacheKey(): string
    {
        return 'wallet:cash-ratio';
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
            'bootstrappers',
        ];

        foreach ($keys as $key) {
            $this->cache->forget($key);
        }
    }
}
