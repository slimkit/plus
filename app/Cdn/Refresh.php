<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
