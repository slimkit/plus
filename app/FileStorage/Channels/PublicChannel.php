<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Channels;

use Illuminate\Http\Request;
use Zhiyi\Plus\AppInterface;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\Task;
use Zhiyi\Plus\FileStorage\FileMetaInterface;

class PublicChannel extends AbstractChannel
{
    protected $app;

    public function __construct(AppInterface $app)
    {
        $this->app = $app;
    }

    public function createTask(): TaskInterface
    {   
        return $this->filesystem->createTask($this->request, $this->resource);
    }

    public function meta(): FileMetaInterface
    {
        return $this->filesystem->meta($this->resource);
    }

    public function url(?string $rule = null): string
    {
        return $this->filesystem->url($this->resource->getPath(), $rule);
    }

    public function callback(): void
    {}
}
