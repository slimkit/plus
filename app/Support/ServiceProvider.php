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
     * 转换处理方法名称为显示名称.
     *
     * @param string $handle
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function formatHandleToDisplay(string $handle): string
    {
        if (strtolower(substr($handle, -6)) === 'handle') {
            $handle = substr($handle, 0, -6);
        }

        return str_replace('_', '-', snake_case($handle));
    }

    /**
     * 转换处理方法为类方法名称.
     *
     * @param string $handle
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function formatHandleToMethod(string $handle): string
    {
        if (strtolower(substr($handle, -6)) === 'handle') {
            $handle = substr($handle, 0, -6);
        }

        return camel_case(str_replace('-', '_', $handle.'_handle'));
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
                $command->info(sprintf('  - %s', $this->formatHandleToDisplay($method)));
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
