<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes();

        require base_path('routes/channels.php');
    }

    /**
     * Broadcast routes.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function routes()
    {
        Broadcast::routes();
    }
}
