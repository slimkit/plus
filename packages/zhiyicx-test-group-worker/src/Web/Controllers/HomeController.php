<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Controllers;

use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    /**
     * Create the Controller instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * The test group worker entry.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index()
    {
        return view('test-group-worker::welcome');
    }
}
