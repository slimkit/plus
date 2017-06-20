<?php

namespace Zhiyi\Plus\Contracts\Cdn;

interface UrlGenerator
{
    /**
     * Generator an absolute URL to the given path.
     *
     * @param string $filename
     * @param array $extra "[float $width, float $height, int $quality]"
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function url(string $filename, array $extra = []): string;
}
