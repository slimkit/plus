<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

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
        $this->publishHandler();
        $this->registerObserves();

        // Load views.
        $this->loadViewsFrom(dirname(__DIR__).'/views/', 'feed:view');

        // Register migration files.
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/database/migrations',
        ]);

        $this->publishes([
            dirname(__DIR__).'/config/feed.php' => $this->app->configPath('feed.php'),
        ], 'config');

        $this->publishes([
            dirname(__DIR__).'/assets' => $this->app->PublicPath().'/assets/feed',
        ], 'public');

        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'feed' => [
                    'reward' => (bool) $this->app->make(ConfigRepository::class)->get('feed.reward'),
                    'paycontrol' => (bool) $this->app->make(ConfigRepository::class)->get('feed.paycontrol'),
                    'items' => (array) $this->app->make(ConfigRepository::class)->get('feed.items'),
                    'limit' => (int) $this->app->make(ConfigRepository::class)->get('feed.limit'),
                ],
            ];
        });

        $this->app->make(PinnedsNotificationEventer::class)->listen(function () {
            return [
                'name' => 'feeds',
                'namespace' => \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned::class,
                'owner_prefix' => 'target_user',
                'wherecolumn' => function ($query) {
                    return $query->where('expires_at', null)->where('channel', 'comment')->whereExists(function ($query) {
                        return $query->from('feeds')->whereRaw('feed_pinneds.raw = feeds.id')->where('deleted_at', null);
                    })->whereExists(function ($query) {
                        return $query->from('comments')->whereRaw('feed_pinneds.target = comments.id');
                    });
                },
            ];
        });
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

        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/feed.php', 'feed'
        );

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
     * Publish handler.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function publishHandler()
    {
        PackageHandler::loadHandleFrom('feed', FeedPackageHandler::class);
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
