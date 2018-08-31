<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Http;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

class MakeRoutes
{
    protected $router;

    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
    }

    public function register(): void
    {
        $this->registerLocalFilesystemRoutes();
        $this->registerChannelCallbackRoutes();
        $this->registerCreateTaskRoutes();
    }

    protected function registerLocalFilesystemRoutes(): void
    {
        $this->router->group(['prefix' => 'storage'], function (RegistrarContract $router) {
            $router
                ->get('{channel}:{path}', Controllers\Local::class.'@get')
                ->name('storage:local-get');
            $router
                ->put('{channel}:{path}', Controllers\Local::class.'@put')
                ->name('storage:local-put');
        });
    }

    protected function registerChannelCallbackRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage/{channel}:{path}', Controllers\Callback::class)
                ->name('storage:callback');
        });
    }

    protected function registerCreateTaskRoutes(): void
    {
        $this->router->group(['prefix' => 'api/v2'], function (RegistrarContract $router) {
            $router
                ->post('storage', Controllers\CreateTask::class)
                ->name('storage:create-task');
        });
    }
}
