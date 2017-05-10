<?php

namespace Zhiyi\Plus\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Zhiyi\Plus\Support\Configuration;

class LoadConfiguration
{
    protected $app;
    protected $configuration;

    public function __construct(Application $app, Configuration $configuration)
    {
        $this->app = $app;
        $this->configuration = $configuration;
    }

    public function handle()
    {
        static $loaded = false;
        if ($loaded) {
            return ;
        }

        $this->app->config->set(
            $this->configuration->getConfigurationBase()
        );
    }
}
