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

namespace SlimKit\PlusCheckIn\Providers;

use Zhiyi\Plus\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register the model service.
     *
     * @return void
     */
    public function register()
    {
        // Register morph map for polymorphic relations.
        $this->registerMorphMap();

        // Register user model macros tu the application.
        $this->registerUserMacros();
    }

    /**
     * Register user model macros tu the application.
     *
     * @return void
     */
    protected function registerUserMacros()
    {
        // User check-in logs.
        User::macro('checkinLogs', function () {
            return $this->hasMany(\SlimKit\PlusCheckIn\Models\CheckinLog::class, 'user_id', 'id');
        });
    }

    /**
     * Register morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        $this->morphMap([
            'checkin-logs' => \SlimKit\PlusCheckIn\Models\CheckinLog::class,
        ]);
    }

    /**
     * Set or get the morph map for polymorphic relations.
     *
     * @param array|null $map
     * @param bool $merge
     * @return array
     */
    protected function morphMap(array $map = null, bool $merge = true): array
    {
        return Relation::morphMap($map, $merge);
    }
}
