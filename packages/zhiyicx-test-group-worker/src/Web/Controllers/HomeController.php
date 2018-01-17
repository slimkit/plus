<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Web\Middleware\AssignAccessToken;

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
        $this->middleware(AssignAccessToken::class);
    }

    /**
     * The test group worker entry.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $variables = [
            'accessToken' => $request->session()->get('access_token'),
            'user' => $request->user(),
        ];

        return view('test-group-worker::app', $variables);
    }
}
