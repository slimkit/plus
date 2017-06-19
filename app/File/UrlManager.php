<?php

namespace Zhiyi\Plus\File;

use Zhiyi\Plus\Support\FileUrlGenerator;
use Zhiyi\Plus\Contracts\File\Factory as FactoryContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class UrlManager implements FactoryContract
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
     * @param string|null $name
     * @return \Zhiyi\Plus\Contracts\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function generator(string $name = null): UrlGenerator
    {
        $name = $name ?: $this->getDefaulrGennerator();

        return $this->generators[$name] ?? $this->resolve($name);
    }

    /**
     * Make a file url.
     *
     * @param \Zhiyi\Plus\Models\File $file
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function make(File $file, array $extra = [], string $name = null): string
    {
        $generator = $this->generator($name);

        if ($generator instanceof FileUrlGenerator) {
            $generator->setFile($file);
        }

        return $generator->to($file->filename, $extra);
    }

    /**
     * Resolve the given generator.
     *
     * @param string $name
     * @return \Zhiyi\Plus\Contracts\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolve(string $name): UrlGenerator
    {
        return $this->generators[$name] = $this->app->make(
            $this->getGeneratorAbstract($name)
        );
    }

    /**
     * Get a generator abstract.
     *
     * @param string|null $name
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getGeneratorAbstract(string $name = null): string
    {
        return FileUrlGenerator::getGeneratorAbstract($name);
    }
}
