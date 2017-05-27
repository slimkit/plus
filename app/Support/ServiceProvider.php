<?php

namespace Zhiyi\Plus\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Load service provider.
     *
     * @param array|string $provider
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function loadProvider($providers)
    {
        if (is_string($providers)) {
            $providers = [$providers];
        }

        $this->app->make(ProviderRepository::class)->load($providers);
    }

    /**
     * Service provider list handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function listHandle($command)
    {
        $command->line('ThinkSNS+ <info>1.0.0-alpha.1<info>');
        $command->line('');
        $command->comment('Usage:');
        $command->line(sprintf('  package:run %s [<handle>]', $command->argument('package')));
        $command->line('');
        $command->comment('Available Handles:');
        foreach (get_class_methods($this) as $method) {
            if (strtolower(substr($method, -6)) === 'handle') {
                $command->info(sprintf('  - %s', substr($method, 0, -6)));
            }
        }
    }

    /**
     * Service provider default handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function defaultHandle($command)
    {
        $command->call(
            'package:run',
            [
                'package' => $command->argument('package'),
                'handle' => 'list',
            ]
        );
    }
}
