<?php

namespace Zhiyi\Plus\Repository;

use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Illuminate\Cache\TaggableStore as CacheTaggableStore;
use Illuminate\Contracts\Cache\Factory as CacheFactoryContract;

class PaidNode
{
    /**
     * Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cache;

    /**
     * Create the cash type respositorie.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(CacheFactoryContract $cache)
    {
        $this->cache = $cache;
    }

    public function find(string $node)
    {
        $value = $this->cache(function ($cache) use ($node) {
            if (! $cache->has($node)) {
                return false;
            }

            return $cache->get($node);
        });

        if ($value !== false) {
            return $value;
        }

        $value = PaidNodeModel::find($node);

        return $this->cache(function ($cache) use ($node, $value) {
            $cache->forever($node, $value);

            return $value;
        }) || $value;
    }

    public function save(string $node)

    public function cacheTags(): array
    {
        return ['paid-nodes'];
    }

    public function cache(callable $callable)
    {
        if (! $this->cache->getStore() instanceof CacheTaggableStore) {
            return false;
        }

        return call_user_func_array($callable, [$this->cache->tags(
            $this->cacheTags()
        )]);
    }
}
