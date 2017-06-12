<?php

namespace Zhiyi\Plus\Repository;

use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class WalletCashMinAmount
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
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get wallet min amount.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(): int
    {
        if ($this->cache->has($this->cacheKey())) {
            return $this->cache->get($this->cacheKey());
        }

        $model = CommonConfig::firstOrCreate(
            ['name' => 'cash:min-amount', 'namespace' => 'wallet'],
            ['value' => 0]
        );

        $this->cache->forever($this->cacheKey(), $amount = intval($model->value));

        return $amount;
    }

    /**
     * Save wallet min amount.
     *
     * @param int $amount
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(int $amount)
    {
        CommonConfig::updateOrCreate(
            ['name' => 'cash:min-amount', 'namespace' => 'wallet'],
            ['value' => $amount]
        );

        $this->flush();
        $this->cache->forever($this->cacheKey(), $amount);
    }

    /**
     * Get the config cache key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function cacheKey(): string
    {
        return 'wallet:cash-min-amount';
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
