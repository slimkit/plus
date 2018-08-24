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

namespace Zhiyi\Plus\Cache;

use Closure;
use Zhiyi\Plus\Support\Setting;
use Illuminate\Contracts\Cache\Factory as CacheInterface;

class DoubleBackup
{
    /**
     * Cache manager.
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cache;

    /**
     * Setting util.
     * @var \Zhiyi\Plus\Support\Setting
     */
    protected $setting;

    /**
     * Create a double backup cacher.
     * @param \Illuminate\Contracts\Cache\Factory $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->setting = $this->createSetting();
    }

    /**
     * Get cache.
     * @param string $index
     * @param int $minutes
     * @param \Closure $task
     * @return mixed
     */
    public function get(string $index, int $minutes, Closure $task)
    {
        $mainIndex = $index.'/main';
        $backupIndexes = [
            $index.'/backup-0',
            $index.'/backup-1',
        ];

        if ($this->cache->has($mainIndex)) {
            return $this->randomRead(array_merge($backupIndexes, [$mainIndex]), function (array $indexes) {
                return $this->randomRead($indexes);
            });
        } elseif ($this->setting->get($taskKey = 'cache://tasks/'.$index)) {
            return null;
        }

        $this->setting->set($taskKey, true);
        $this->cache->put($mainIndex, $contents = $task(), $expiresAt = now()->addMinutes($minutes));
        foreach ($backupIndexes as $key => $value) {
            $temExpiresAt = $expiresAt->addMinutes(
                $minutes / ($key + 1) / 2
            );
            $this->cache->put($value, $contents, $temExpiresAt);
        }

        return $contents;
    }

    /**
     * Create a setting util.
     * @return \Zhiyi\Plus\Support\Setting
     */
    public function createSetting(): Setting
    {
        return Setting::create('cache://double-backup');
    }

    /**
     * Random read cache.
     * @param array $indexes
     * @param \Closure $next
     * @return mixed
     */
    protected function randomRead(array $indexes, Closure $next)
    {
        $indexKey = rand(0, count($indexes) - 1);
        $index = $indexes[$indexKey];

        if ($this->cache->has($index)) {
            return $this->cache->get($index);
        }

        unset($indexes[$indexKey]);

        return $next(array_values($indexes));
    }
}
