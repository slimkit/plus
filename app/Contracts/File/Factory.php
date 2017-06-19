<?php

namespace Zhiyi\Plus\Contracts\File;

use Zhiyi\Plus\Models\File;

interface Factory
{
    /**
     * Get URL generator.
     *
     * @param string|null $name
     * @return \Zhiyi\Plus\Contracts\UrlGenerator
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function generator(string $name = null): UrlGenerator;

    /**
     * Make a file url.
     *
     * @param \Zhiyi\Plus\Models\File $file
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function make(File $file, array $extra = []): string;
}
