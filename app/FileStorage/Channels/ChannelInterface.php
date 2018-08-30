<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Channels;

use Illuminate\Http\Request;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\FileMateInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface;

interface ChannelInterface
{
    public function setResource(ResourceInterface $resource): void;

    public function setRequest(Request $request): void;

    public function setFilesystem(FilesystemInterface $filesystem): void;

    public function createTask(): TaskInterface;

    public function meta(): FileMateInterface;
}
