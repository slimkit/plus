<?php

namespace Zhiyi\Plus\Repository;

use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class UserWalletCashType
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
     * Get wallet types.
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
            ['name' => 'cash', 'namespace' => 'wallet'],
            ['value' => '[]']
        );

        $this->cache->forever($this->cacheKey(), $types = json_decode($model->value, true));

        return $types;
    }

    /**
     * 储存或者更新设置.
     *
     * @param array $types
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(array $types)
    {
        CommonConfig::updateOrCreate(
            ['name' => 'cash', 'namespace' => 'wallet'],
            ['value' => json_encode($types)]
        );

        $this->flush();
        $this->cache->forever($this->cacheKey(), $types);
    }

    /**
     * Get the config cache key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function cacheKey(): string
    {
        return 'wallet:cash-types';
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
