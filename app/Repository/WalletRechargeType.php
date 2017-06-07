<?php

namespace Zhiyi\Plus\Repository;

use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Contracts\Cache\Repository as ContractsCacheRepository;

class WalletRechargeType
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
     * Get wallet recharge types.
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
            ['name' => 'wallet:recharge-type', 'namespace' => 'common'],
            ['value' => '[]']
        );

        $this->cache->forever($this->cacheKey(), $types = json_decode($model->value, true));

        return $types;
    }

    /**
     * Save wallet recharge types.
     *
     * @param array $types
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(array $types)
    {
        CommonConfig::updateOrCreate(
            ['name' => 'wallet:recharge-type', 'namespace' => 'common'],
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
        return 'wallet:recharge-type';
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
