<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Channels;

use Illuminate\Http\Request;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface;

abstract class AbstractChannel implements ChannelInterface
{
    protected $resource;
    protected $request;
    protected $filesystem;

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setResource(ResourceInterface $resource): void
    {
        $this->resource = $resource;
    }

    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    public function put($context): bool
    {
        return $this->filesystem->put($this->resource->getPath(), $context);
    }
}
