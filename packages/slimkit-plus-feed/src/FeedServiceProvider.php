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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;

class FeedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->routeMap();
        $this->registerObserves();

        // Load views.
        $this->loadViewsFrom(dirname(__DIR__).'/views/', 'feed:view');

        // Register migration files.
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/database/migrations',
        ]);

        $this->publishes([
            dirname(__DIR__).'/assets' => $this->app->PublicPath().'/assets/feed',
        ], 'feed:resource/assets');

        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'feed' => [
                    'reward' => setting('feed', 'reward-switch'),
                    'paycontrol' => setting('feed', 'pay-switch', false),
                    'items' => setting('feed', 'pay-items', []),
                    'limit' => setting('feed', 'pay-word-limit', 50),
                ],
            ];
        });

        $this->app->make(PinnedsNotificationEventer::class)->listen(function () {
            return [
                'name' => 'feeds',
                'namespace' => \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned::class,
                'owner_prefix' => 'target_user',
                'wherecolumn' => function ($query) {
                    return $query
                        ->whereNull('expires_at')
                        ->where('channel', 'comment')
                        ->has('feed')
                        ->has('comment');
                // return $query->where('expires_at', null)->where('channel', 'comment')->whereExists(function ($query) {
                    //     return $query->from('feeds')->whereRaw('feed_pinneds.raw = feeds.id')->where('deleted_at', null);
                    // })->whereExists(function ($query) {
                    //     return $query->from('comments')->whereRaw('feed_pinneds.target = comments.id');
                    // });
                },
            ];
        });
        // dd($this->moduleName);
        // $this
        //     ->app
        //     ->make()
        //     ->load(__DIR__.'/../database/factories');
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)->loadManageFrom('动态管理', 'feed:admin', [
            'route' => true,
            'icon' => asset('assets/feed/feed-icon.png'),
        ]);

        Relation::morphMap([
            'feeds' => Models\Feed::class,
        ]);
    }

    /**
     * Register model events.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerObserves()
    {
        Feed::observe(Observers\FeedObserver::class);
    }

    /**
     * Register route.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function routeMap()
    {
        if (! $this->app->routesAreCached()) {
            $this->app->make(RouteRegistrar::class)->all();
        }
    }
}
