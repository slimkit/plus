<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

use Zhiyi\Plus\AppInterface;
use Illuminate\Support\Manager;
use function Zhiyi\Plus\setting;

class FilesystemManager extends Manager
{
    public function __construct(AppInterface $app)
    {
        parent::__construct($app);
    }

    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return setting('core', 'file:default-filesystem', 'local');
    }

    public function createLocalDriver(): Filesystems\FilesystemInterface
    {
        $filesystem = $this
            ->app
            ->make(\Illuminate\Contracts\Filesystem\Factory::class)
            ->disk(setting('core', 'file:local-filesystem-select', 'local'));

        return new Filesystems\LocalFilesystem($filesystem);
    }
}
