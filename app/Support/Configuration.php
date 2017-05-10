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

    public function getConfiguration(): RepositoryContract
    {
        if (! $this->files->exists($file = $this->app->vendorEnvironmentYamlFilePath())) {
            return [];
        }

        return new Repository($this->app->make(Parser::class)->parse(
            $this->files->get($file)
        ));
    }

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
