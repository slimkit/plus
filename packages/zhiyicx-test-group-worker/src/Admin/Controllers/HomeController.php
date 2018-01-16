<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Admin\Controllers;

class HomeController
{
    public function index()
    {
        return trans('test-group-worker::messages.success');
    }
}
