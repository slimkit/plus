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

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Zhiyi\Plus\Models\Comment::observe(
            \Zhiyi\Plus\Observers\CommentObserver::class
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMorpMap();
    }

    /**
     * Register model morp map.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerMorpMap()
    {
        $types = $this->app->make(\Zhiyi\Plus\Types\Models::class);
        $this->setMorphMap(
            $types->all(\Zhiyi\Plus\Types\Models::KEY_BY_CLASS_ALIAS)
        );
    }

    /**
     * Set the morph map for polymorphic relations.
     *
     * @param array|null $map
     * @param bool|bool $merge
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function setMorphMap(array $map = null, bool $merge = true)
    {
        return Relation::morphMap($map, $merge);
    }
}
