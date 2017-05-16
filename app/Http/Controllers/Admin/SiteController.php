<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Area;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class SiteController extends Controller
{
    /**
     * The store CommonConfig instance.
     *
     * @var Zhiyi\Plus\Models\CommonConfig
     */
    protected $commonCinfigModel;

    protected $app;

    /**
     * Construct handle.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the website info.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function get(Request $request, Repository $config, ResponseFactory $response)
    {
        if (! $request->user()->can('admin:site:base')) {
            return response()->json([
                'message' => '没有权限查看该项信息',
            ])->setStatusCode(403);
        }

        $name = $config->get('app.name', 'ThinkSNS+');
        $keywords = $config->get('app.keywords');
        $description = $config->get('app.description');
        $icp = $config->get('app.icp');

        return $response->json([
            'name' => $name,
            'keywords' => $keywords,
            'description' => $description,
            'icp' => $icp,
        ])->setStatusCode(200);
    }

    /**
     * 更新网站基本信息.
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function updateSiteInfo(Request $request, Configuration $config, ResponseFactory $response)
    {
        if (! $request->user()->can('admin:site:base')) {
            return response()->json([
                'message' => '没有权限更新该信息',
            ])->setStatusCode(403);
        }

        $keys = ['name', 'keywords', 'description', 'icp'];
        // $requestSites = array_filter($request->only($keys));

        $site = [];
        foreach ($request->only($keys) as $key => $value) {
            $site['app.'.$key] = $value;
        }
        $config->set($site);

        return $response->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * 获取全部地区.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function areas(Request $request)
    {
        if (! $request->user()->can('admin:area:show')) {
            return response()->json([
                'message' => '你没有权限查看地区数据',
            ])->setStatusCode(403);
        }

        $expiresAt = Carbon::now()->addMonth(12);
        $areas = Cache::remember('areas', $expiresAt, function () {
            return Area::all();
        });

        return response()->json($areas ?: [])->setStatusCode(200);
    }

    /**
     * 添加地区.
     *
     * @param Request $request
     *
     * @return mixed [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function doAddArea(Request $request)
    {
        if (! $request->user()->can('admin:area:add')) {
            return response()->json([
                'error' => ['你没有添加地区权限'],
            ])->setStatusCode(403);
        }

        $name = $request->input('name');
        $extends = $request->input('extends', '');
        $pid = $request->input('pid', 0);

        if (! $name) {
            return response()->json([
                'error' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif ($pid && ! Area::find($pid)) {
            return response()->json([
                'error' => ['pid' => '父地区不存在'],
            ])->setStatusCode(422);
        }

        $area = new Area();
        $area->name = $name;
        $area->extends = $extends;
        $area->pid = $pid;
        if (! $area->save()) {
            return response()->json([
                'error' => ['数据库保存失败'],
            ])->setStatusCode(500);
        }

        Cache::forget('areas');

        return response()->json($area)->setStatusCode(201);
    }

    /**
     * 删除地区.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function deleteArea(Request $request, int $id)
    {
        if (! $request->user()->can('admin:area:delete')) {
            return response()->json([
                'error' => ['你没有权限删除地区'],
            ])->setStatusCode(403);
        }

        $notEmpty = Area::byPid($id)->first();
        if ($notEmpty) {
            return response()->json([
                'error' => '请先删除该地区下级地区',
            ])->setStatusCode(422);
        }

        Area::where('id', $id)->delete();
        Cache::forget('areas');

        return response('', 204);
    }

    /**
     * 更新地区数据.
     *
     * @param Request $request
     * @param Area    $area
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function patchArea(Request $request, Area $area)
    {
        if (! $request->user()->can('admin:area:update')) {
            return response()->json([
                'error' => ['你没有更新地区权限'],
            ])->setStatusCode(403);
        }

        $key = $request->input('key');
        $value = $request->input('value', '');

        if (! in_array($key, ['name', 'extends'])) {
            return response()->json([
                'error' => ['请求不合法'],
            ])->setStatusCode(422);
        } elseif ($key == 'name' && ! $value) {
            return response()->json([
                'error' => ['name' => '地区名称不能为空'],
            ])->setStatusCode(422);
        }

        $area->$key = $value;
        if (! $area->save()) {
            return response()->json([
                'error' => ['数据更新失败'],
            ])->setStatusCode(500);
        }

        Cache::forget('areas');

        return response()->json([
            'message' => [$key => '更新成功'],
        ])->setStatusCode(201);
    }
}
