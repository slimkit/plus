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

namespace Zhiyi\Plus\Packages\Music\Providers;

use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(
            component_base_path('/router.php')
        ); // 路由注入

        $this->publishes([
            component_base_path('/assets') => $this->app->PublicPath().'/assets/music',
        ]); // 静态资源

        // Register a database migration path.
        $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations');
    }

    public function register()
    {
        Relation::morphMap([
            'musics' => Music::class,
            'music_specials' => MusicSpecial::class,
        ]);
        $this->app->make(ManageRepository::class)->loadManageFrom('音乐', 'music:list', [
            'route' => true,
            'icon' => 'MU',
        ]);
    }
}
