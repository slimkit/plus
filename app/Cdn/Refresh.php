<?php

namespace Zhiyi\Plus\Cdn;

class Refresh
{
    /**
     * Files.
     *
     * @var array
     */
    protected $files = [];

    /**
     * Dirs.
     *
     * @var array
     */
    protected $dirs = [];

    /**
     * Create the refresh.
     *
     * @param array $files
     * @param array $dirs
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(array $files = [], array $dirs = [])
    {
        $this->files = $files;
        $this->dirs = $dirs;
    }

    /**
     * Get files.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Set files.
     *
     * @param array $files
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get dirs.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getDirs(): array
    {
        return $this->dirs;
    }

    /**
     * Set dirs.
     *
     * @param array $dirs
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setDirs(array $dirs)
    {
        $this->dirs = $dirs;

        return $this;
    }
}
