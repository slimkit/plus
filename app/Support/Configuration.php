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

namespace Zhiyi\Plus\Support;

use Illuminate\Config\Repository;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class Configuration
{
    protected $app;
    protected $files;

    /**
     * Create basic information.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->files = new Filesystem();
    }

    /**
     * Get vendor configuration.
     *
     * @return \Illuminate\Contracts\Config\Repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getConfiguration(): RepositoryContract
    {
        $items = [];
        if ($this->files->exists($file = $this->app->appYamlConfigureFile())) {
            $items = $this->app->make(Parser::class)->parse(
                $this->files->get($file)
            ) ?: $items;
        }

        return new Repository($items);
    }

    /**
     * Get the configuration into a primary array, and the application scenario may be overridden in the Repository.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getConfigurationBase(): array
    {
        return $this->parse(
            $this->getConfiguration()->all()
        );
    }

    /**
     * Set configuration save to file.
     *
     * @param array|string $key
     * @param mixed $value
     * @return \Illuminate\Contracts\Config\Repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function set($key, $value = null): RepositoryContract
    {
        $config = $this->getConfiguration();
        $config->set($key, $value);

        // Perform the configuration save operation.
        $this->save($config);

        return $config;
    }

    /**
     * Save the custom configuration into the YAML file.
     *
     * @param RepositoryContract $config
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save(RepositoryContract $config)
    {
        // Created if the target directory does not exist.
        //
        // This is useful in custom configuration file storage,
        // and you can avoid the direct save of the error.
        $target = dirname($this->app->appYamlConfigureFile());
        if (! $this->files->isDirectory($target)) {
            $this->files->makeDirectory($target, 0755, true);
        }

        // Save the configuration into the YAML file.
        $this->files->put(
            $this->app->appYamlConfigureFile(),
            $this->app->make(Dumper::class)->dump($config->all(), 10)
        );
    }

    /**
     * Converts a multidimensional array to a basic array of point divisions.
     *
     * @param array $target 目标数组
     * @param string $pre 数组前缀
     * @param array $org 原始数组
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function parse(array $target, string $pre = '', array $org = []): array
    {
        if (! is_array($target)) {
            return [];
        }

        foreach ($target as $key => $value) {
            $key = $pre ? $pre.'.'.$key : $key;
            $value = value($value);

            if (is_array($value) && array_keys($value) !== range(0, count($value) - 1)) {
                $org = $this->parse($value, $key, $org);
                continue;
            }

            $org[$key] = $value;
        }

        return $org;
    }
}
