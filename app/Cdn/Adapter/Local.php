<?php

namespace Zhiyi\Plus\Cdn\Adapter;

use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as FileUrlGeneratorContract;

class Local implements FileUrlGeneratorContract
{
    public function url(string $filename, array $extra = []): string
    {
        //
    }
}
