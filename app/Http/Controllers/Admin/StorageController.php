<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Services\Storage as StorageService;

class StorageController extends Controller
{
    /**
     * 获取所有的储存引擎.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showEngines(Request $request, StorageService $storageService)
    {
        if (! $request->user()->can('admin:storages')) {
            return response()->json([
                'message' => '你没有权限查看该项',
            ])->setStatusCode(403);
        }

        $engines = $storageService->getEngines();
        $selected = $storageService->getEngineSelect();
        $optionsValues = $storageService->getEngineOption($selected);

        return response()->json([
            'engines' => $engines,
            'selected' => $selected,
            'optionsValues' => $optionsValues,
        ])->setStatusCode(200);
    }

    /**
     * 修改用户选择引擎的表单配置.
     *
     * @param Request $request
     * @param string $engine
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updateEngineOption(Request $request, StorageService $storageService, string $engine)
    {
        if (! $request->user()->can('admin:storages')) {
            return response()->json([
                'message' => '你没有权限更新储存设置',
            ])->setStatusCode(403);
        }

        $engines = $storageService->getEngines();
        if (! in_array($engine, array_keys($engines))) {
            return response()->json([
                'engine' => '选择的储存引擎不存在',
            ])->setStatusCode(422);
        }

        $options = $request->input('options');
        DB::transaction(function () use ($storageService, $engine, $options) {
            $storageService->setEngineSelect($engine);
            $storageService->setEngineOption($engine, $options);
        });

        return response()->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * 获取引擎配置.
     *
     * @param StorageService $service
     * @param string $engine
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showEngineOption(StorageService $service, string $engine)
    {
        return response()->json(
            $service->getEngineOption($engine)
        )->setStatusCode(200);
    }
}
