<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Plus\Support\ManageRepository;

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
