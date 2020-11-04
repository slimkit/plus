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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Plus\Models\User;
use function Zhiyi\Plus\setting;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom(
            dirname(dirname(__DIR__)).'/database/migrations'
        );

        $this->publishes([
            dirname(__DIR__).'/../resource' => $this->app->PublicPath()
                .'/assets/news',
        ], 'public');

        // Register view namespace.
        $this->loadViewsFrom(dirname(__DIR__).'/../view', 'plus-news');

        $this->loadRoutesFrom(
            dirname(__DIR__).'/../router.php'
        );

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)
            ->listen('v2', function () {
                return [
                    'news' => [
                        'contribute'     => setting('news', 'contribute', [
                            'pay'      => true,
                            'verified' => true,
                        ]),
                        'pay_contribute' => setting('news', 'contribute-amount',
                            100),
                    ],
                ];
            });

        // 注册置顶审核通知事件
        $this->app->make(PinnedsNotificationEventer::class)->listen(function (
        ) {
            return [
                'name'         => 'news-comments',
                'namespace'    => \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned::class,
                'owner_prefix' => 'target_user',
                'wherecolumn'  => function ($query) {
                    return $query->where('expires_at', null)
                        ->where('channel', 'news:comment')
                        ->whereExists(function ($query) {
                            return $query->from('news')
                                ->whereRaw('news_pinneds.target = news.id')
                                ->where('deleted_at', null);
                        })->whereExists(function ($query) {
                            return $query->from('comments')
                                ->whereRaw('news_pinneds.raw = comments.id');
                        });
                },
            ];
        });

        // $this->app->make(Factory::class)->load(__DIR__.'/../../database/factories');
        // $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__
        //     .'/../../database/factories');

        Relation::morphMap([
            'news' => News::class,
        ]);
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)
            ->loadManageFrom('资讯', 'news:admin', [
                'route' => true,
                'icon'  => asset('assets/news/news-icon.png'),
            ]);

        User::macro('newsCollections', function () {
            return $this->belongsToMany(News::class, 'news_collections',
                'user_id', 'news_id');
        });
    }
}
