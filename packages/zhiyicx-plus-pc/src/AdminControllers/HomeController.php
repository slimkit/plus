<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    /**
     * pc后台首页
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function show(Request $request, JWTAuthToken $jwt)
    {
        if (! $request->user()) {
            return redirect(route('admin'), 302);
        }

        config('jwt.single_auth', false);

        return view('pcview::admin', [
            'token' => $jwt->create($request->user()),
            'base_url' => route('pc:admin'),
            'csrf_token' => csrf_token(),
            'api' => url('api/v2'),
            'files' => url('/api/v2/files'),
        ]);
    }

    /**
     * 菜单
     * @author 28youth
     * @return array
     */
    protected function menus()
    {
        $components = config('component');
        $menus = [];

        foreach ($components as $component => $info) {
            $info = (array) $info;
            $installer = array_get($info, 'installer');
            $installed = array_get($info, 'installed', false);

            if (! $installed || ! $installer) {
                continue;
            }

            $componentInfo = app($installer)->getComponentInfo();

            if (! $componentInfo) {
                continue;
            }

            $menus[$component] = [
                'name'  => $componentInfo->getName(),
                'icon'  => $componentInfo->getIcon(),
                'logo'  => $componentInfo->getLogo(),
                'admin' => $componentInfo->getAdminEntry(),
            ];
        }

        return $menus;
    }
}
