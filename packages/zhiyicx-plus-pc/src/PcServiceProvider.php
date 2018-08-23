<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\PackageHandler;

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

        // load handle
        PackageHandler::loadHandleFrom('pc', PcPackageHandler::class);

        // load view composers
        View::composer('pcview::widgets.hotusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotUsers');
        View::composer('pcview::widgets.recusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\RecommendUsers');
        View::composer('pcview::widgets.checkin', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\CheckIn');
        View::composer('pcview::widgets.hotnews', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotNews');
        View::composer('pcview::widgets.ads', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\Ads');
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
