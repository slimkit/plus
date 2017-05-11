<?php

namespace Zhiyi\Plus\Support;

use Illuminate\Config\Repository;
use Symfony\Component\Yaml\Parser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class Configuration
{
    protected $app;
    protected $files;

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
        if ($this->files->exists($file = $this->app->vendorEnvironmentYamlFilePath())) {
            $items = $this->app->make(Parser::class)->parse(
                $this->files->get($file)
            ) ?: $items;
        }

        return new Repository($items);
    }

    /**
     * 获取转化为一级数组的配置，应用场景可能在 Repository 中的覆盖.
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

    protected function parse(array $target, string $pre = '', array $org = []): array
    {
        if (! is_array($target)) {
            return [];
        }

        foreach ($target as $key => $value) {
            $key = $pre ? $pre.'.'.$key : $key;
            $value = value($value);

            if (is_array($value) || (is_object($value) && $value = (array) $value)) {
                $org = $this->parse($value, $key, $org);
                continue;
            }

            $org[$key] = $value;
        }

        return $org;
    }
}
