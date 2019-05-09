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

namespace Zhiyi\Plus\FileStorage\Providers;

use Zhiyi\Plus\AppInterface;
use Zhiyi\Plus\FileStorage\Storage;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\FileStorage\ChannelManager;
use Zhiyi\Plus\FileStorage\Http\MakeRoutes;
use Zhiyi\Plus\FileStorage\StorageInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Zhiyi\Plus\FileStorage\Validators\Rulers\ValidatorRulesRegister;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The app register.
     *
     * @return void
     */
    public function register() {
        // Register StorageInterface instance.
        $this->app->singleton(StorageInterface::class,
            function (AppInterface $app)
            {
                $manager = $this->app->make(ChannelManager::class);

                return new Storage($app, $manager);
            });
    }

    /**
     * The app bootstrap handler.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot() {
        // Register routes.
        $this->app->make(MakeRoutes::class)->register();

        // Register validate rules.
        $this->app->make(ValidatorRulesRegister::class)->register();
    }
}
