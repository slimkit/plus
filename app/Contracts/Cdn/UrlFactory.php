<?php

namespace Zhiyi\Plus\Contracts\Cdn;

use Zhiyi\Plus\Models\File;

interface UrlFactory
{
    /**
     * Get URL generator.
     *
     * @param string $name
     * @return \Zhiyi\Plus\Contracts\Cdn\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function generator(string $name = ''): UrlGenerator;

    /**
     * Make a file url.
     *
     * @param \Zhiyi\Plus\Models\File $file
     * @param array $extra
     * @param string $name
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function make(File $file, array $extra = [], string $name = ''): string;
}
