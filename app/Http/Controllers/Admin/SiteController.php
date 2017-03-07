<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\Area;

class SiteController extends Controller
{
    /**
     * The store CommonConfig instance.
     *
     * @var Zhiyi\Plus\Models\CommonConfig
     */
    protected $commonCinfigModel;

    /**
     * Get the website info.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function get()
    {
        $sites = $this->newCommonConfigModel()
            ->byNamespace('site')
            ->whereIn('name', ['title', 'keywords', 'description', 'icp'])
            ->get();

        $sites = $sites->mapWithKeys(function ($item) {
            return [$item['name'] => $item['value']];
        });

        return response()->json($sites)->setStatusCode(200);
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
    public function updateSiteInfo(Request $request)
    {
        $keys = ['title', 'keywords', 'description', 'icp'];
        $requestSites = array_filter($request->only($keys));

        $sites = $this->newCommonConfigModel()
            ->byNamespace('site')
            ->whereIn('name', $keys)
            ->get()
            ->keyBy('name');

        $callback = function () use ($sites, $requestSites) {
            foreach ($requestSites as $name => $value) {
                $model = $sites[$name] ?? false;
                if (!$model) {
                    $model = new CommonConfig();
                    $model->namespace = 'site';
                    $model->name = $name;
                    $model->value = $value;
                    $model->save();
                    continue;
                }

                $this->newCommonConfigModel()
                    ->byNamespace('site')
                    ->byName($name)
                    ->update([
                        'value' => $value,
                    ]);
            }

            return response()->json([
                'message' => '更新成功',
            ])->setStatusCode(201);
        };
        $callback->bindTo($this);

        return $this->dbTransaction($callback);
    }

    /**
     * 获取全部地区.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function areas()
    {
        $expiresAt = Carbon::now()->addMonth(12);
        $areas = Cache::remember('areas', $expiresAt, function () {
            return Area::all();
        });

        return response()->json($areas ?: [])->setStatusCode(200);
    }

    /**
     * instance a new model.
     *
     * @return Zhiyi\Plus\Models\CommonConfig::newQuery
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function newCommonConfigModel()
    {
        if (!$this->commonCinfigModel instanceof CommonConfig) {
            $this->commonCinfigModel = new CommonConfig();
        }

        return $this->commonCinfigModel->newQuery();
    }

    /**
     * Static bind DB::transaction .
     *
     * @param Closure $callback
     * @param int     $attempts
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function dbTransaction(Closure $callback, $attempts = 1)
    {
        return DB::transaction($callback, $attempts);
    }
}
