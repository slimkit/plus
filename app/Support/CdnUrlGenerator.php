<?php

namespace Zhiyi\Plus\Support;

use Zhiyi\Plus\Models\File;
use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as FileUrlGeneratorContract;

abstract class CdnUrlGenerator implements FileUrlGeneratorContract
{
    /**
     * File data model.
     *
     * @var \Zhiyi\Plus\Models\File
     */
    protected $file;

    /**
     * Get file data model.
     *
     * @return \Zhiyi\Plus\Models\File
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getFile(): File
    {
        return $this->file;
    }

    /**
     * Set file data model.
     *
     * @param \Zhiyi\Plus\Models\File $file
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setFile(File $file)
    {
        $this->file = $file;

        return $this;
    }
}
