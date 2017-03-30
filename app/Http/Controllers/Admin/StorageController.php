<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

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
    public function showEngines(StorageService $storageService)
    {
        $engines = $storageService->getEngines();
        $selected = $storageService->getEngineSelect();
        $optionsValues = $storageService->getEngineOption($selected);

        return response()->json([
            'engines' => $engines,
            'selected' => $selected,
            'optionsValues' => $optionsValues,
        ])->setStatusCode(200);
    }
}
