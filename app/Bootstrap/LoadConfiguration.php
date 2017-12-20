<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Bootstrap;

use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Foundation\Application;

class LoadConfiguration
{
    protected $app;
    protected $configuration;

    /**
     * Create the bootstrap instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Application $app, Configuration $configuration)
    {
        $this->app = $app;
        $this->configuration = $configuration;
    }

    /**
     *  Run handler.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        static $loaded = false;
        if ($loaded) {
            return;
        }

        $this->app->config->set(
            $this->configuration->getConfigurationBase()
        );
    }
}
