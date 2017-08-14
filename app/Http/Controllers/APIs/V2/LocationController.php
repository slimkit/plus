<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Area as AreaModel;
use Zhiyi\Plus\Models\CommonConfig as ConfigModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class LocationController extends Controller
{
    /**
     * Search locations.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\Area $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function search(Request $request, ResponseFactoryContract $response, AreaModel $model)
    {
        $name = $request->query('name');
        if (! $name) {
            return $response->json(['name' => ['必须输入搜索关键词']], 422);
        }

        $areas = $model->with('parent', 'items')
            ->where('name', 'like', sprintf('%%%s%%', $name))
            ->limit(5)
            ->get();

        $parentTreeCall = function (callable $call, AreaModel $model, $tree = []) {
            if ($model->parent) {
                $tree[] = $model->parent;

                return $call($call, $model->parent, $tree);
            }

            return $tree;
        };

        return $model->getConnection()->transaction(function () use ($areas, $parentTreeCall, $response) {
            return $response->json($areas->map(function (AreaModel $item) use ($parentTreeCall) {
                $item->setHidden(array_merge($item->getHidden(), ['items']));

                return [
                    'items' => count($parentTreeCall($parentTreeCall, $item, [$item])) < 3 ? $item->items : null,
                    'tree' => $item,
                ];
            }))->setStatusCode(200);
        });
    }

    /**
     * 获取热门城市列表.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function hots(ResponseFactoryContract $response, ConfigModel $configMModel)
    {
        $hots = $configMModel->byNamespace('common')
            ->byName('hots_area')
            ->value('value');

        $hots = $hots ? json_decode($hots) : [];

        return $response->json($hots, 200);
    }
}
