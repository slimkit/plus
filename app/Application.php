<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus;

use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    /**
     * The ThinkSNS Plus version.
     *
     * @var string
     */
    const VERSION = '2.0.0';

    /**
     * Create a new Illuminate application instance.
     *
     * @param string|null $basePath
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        // Load configuration after.
        $this->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function ($app) {
            $app->make(\Zhiyi\Plus\Bootstrap\LoadConfiguration::class)
                ->handle();
        });

        // Use environment path.
        $this->useEnvironmentPath($this->appConfigurePath());
    }

    /**
     * Get the version number of the Laravel framework.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getLaravelVersion()
    {
        return parent::VERSION;
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        // Register parent core container aliases.
        parent::registerCoreContainerAliases();

        // Register the app core container aliased.
        foreach ([
            'app' => [static::class],
            'cdn' => [
                \Zhiyi\Plus\Contracts\Cdn\UrlFactory::class,
                \Zhiyi\Plus\Cdn\UrlManager::class,
            ],
            'at-message' => [
                \Zhiyi\Plus\AtMessage\MessageInterface::class,
                \Zhiyi\Plus\AtMessage\Message::class,
            ],
            'at-resource-manager' => [
                \Zhiyi\Plus\AtMessage\ResourceManagerInterface::class,
                \Zhiyi\Plus\AtMessage\ResourceManager::class,
            ],
        ] as $abstract => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($abstract, $alias);
            }
        }
    }

    /**
     * The app configure path.
     * @param  string $path
     * @return string
     */
    public function appConfigurePath(string $path = ''): string
    {
        return $this->basePath().'/storage/configure'.($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the app YAML configure filename.
     * @return string
     */
    public function appYamlConfigureFile(): string
    {
        return $this->appConfigurePath('plus.yml');
    }
}
