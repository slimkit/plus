<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Web\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;
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
        $user = $request->user();
        if (! $user->roles('developer') && ! $user->roles('tester')) {
            abort(403, '您没有权限进入该应用');
        }

        // GitHub Basic Token
        $githubBasicToken = null;
        if ($access = $this->getAccessQuery()->find($user->id)) {
            $githubBasicToken = base64_encode($access->login.':'.$access->password);
        }

        $variables = [
            'accessToken' => $request->session()->get('access_token'),
            'user' => $request->user(),
            'githubBasicToken' => $githubBasicToken,
        ];

        return view('test-group-worker::app', $variables);
    }

    /**
     * Get access model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getAccessQuery(): Builder
    {
        return AccessModel::query();
    }
}
