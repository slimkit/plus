<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Controllers;

class HomeController
{
    public function index()
    {
        return view('test-group-worker::welcome');
    }
}
