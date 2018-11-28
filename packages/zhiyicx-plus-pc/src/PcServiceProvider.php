<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;

class PcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // load views.
        $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'pcview');

        // publish resource
        $this->publishes([
            dirname(__DIR__).'/resources/assets/web' => $this->app->PublicPath().'/assets/pc',
        ], 'pc-public');

        // load routes
        $this->loadRoutesFrom(
            dirname(__DIR__).'/router.php'
        );

        // Register migration files.
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/database/migrations',
        ]);

        $this->publishes([
            dirname(__DIR__).'/config/pc.php' => $this->app->configPath('pc.php'),
        ], 'config');

        // load view composers
        View::composer('pcview::widgets.hotusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotUsers');
        View::composer('pcview::widgets.recusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\RecommendUsers');
        View::composer('pcview::widgets.checkin', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\CheckIn');
        View::composer('pcview::widgets.hotnews', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotNews');
        View::composer('pcview::widgets.hotgroups', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotGroups');
        View::composer('pcview::widgets.incomerank', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\IncomeRank');
        View::composer('pcview::widgets.hotquestions', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotQuestions');
        View::composer('pcview::widgets.questionrank', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\QuestionRank');
        View::composer('pcview::widgets.relevantquestion', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\QuestionRelevant');
        View::composer('pcview::widgets.hottopics', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotTopics');
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)->loadManageFrom('PC端', 'pc:admin', [
            'route' => true,
            'icon' => asset('assets/pc/pc-icon.png'),
        ]);

        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/pc.php', 'pc'
        );
    }

    /**
     * Register route.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function routeMap()
    {
        $this->app->make(RouteRegistrar::class)->all();
    }
}
