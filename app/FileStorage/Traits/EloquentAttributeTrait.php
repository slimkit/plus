<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Traits;

use Zhiyi\Plus\FileStorage\StorageInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\Resource;

trait EloquentAttributeTrait
{
    protected function getStorage(): StorageInterface
    {
        return app(StorageInterface::class);
    }

    protected function parseFile(string $resource): FileMetaInterface
    {
        return $this->getStorage()->meta(new Resource($resource));
    }
}
