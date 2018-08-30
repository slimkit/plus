<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Filesystems;

use Illuminate\Http\Request;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;

interface FilesystemInterface
{
    public function meta(ResourceInterface $resource): ?FileMetaInterface;

    public function url(string $path, ?string $rule = null): string;

    public function delete(string $path): bool;

    public function createTask(Request $request, ResourceInterface $resource): TaskInterface;
}
