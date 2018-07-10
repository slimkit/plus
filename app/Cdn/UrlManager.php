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

namespace Zhiyi\Plus\Cdn;

use Zhiyi\Plus\Models\File;
use Zhiyi\Plus\Support\CdnUrlGenerator;
use Zhiyi\Plus\Contracts\Cdn\UrlFactory as UrlFactoryContract;
use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as UrlGeneratorContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class UrlManager implements UrlFactoryContract
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The arr of resolved URL generator instance.
     *
     * @var array
     */
    protected $generators = [];

    /**
     * Create a file URL generator manager instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * Get URL generator.
     *
     * @param string $driver
     * @return \Zhiyi\Plus\Contracts\Cdn\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function generator(string $driver = ''): UrlGeneratorContract
    {
        $driver = $driver ?: $this->getDefaulrGennerator();

        return $this->generators[$driver] ?? $this->resolve($driver);
    }

    /**
     * Make a file url.
     *
     * @param \Zhiyi\Plus\Models\File $file
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function make(File $file, array $extra = [], string $driver = ''): string
    {
        $generator = $this->generator($driver);

        if ($generator instanceof CdnUrlGenerator) {
            $generator->setFile($file);
        }

        return $generator->url($file->filename, $extra);
    }

    /**
     * Resolve the given generator.
     *
     * @param string $driver
     * @return \Zhiyi\Plus\Contracts\Cdn\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolve(string $driver): UrlGeneratorContract
    {
        return $this->generators[$driver] = $this->app->make(
            $this->getGeneratorAbstract($driver)
        );
    }

    /**
     * Get a generator abstract.
     *
     * @param string $driver
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getGeneratorAbstract(string $driver): string
    {
        return $this->app->config->get(
            sprintf('cdn.generators.%s.driver', $driver)
        ) ?: $driver;
    }

    /**
     * Get default file URL generator.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getDefaulrGennerator(): string
    {
        return $this->app->config['cdn.default'] ?: 'filesystem';
    }
}
