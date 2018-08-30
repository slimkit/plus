<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Http\Controllers;

use Illuminate\Http\Request;

class Callback
{
    public function __invoke(Request $request, string $channel, string $path)
    {
        // too.
    }
}
