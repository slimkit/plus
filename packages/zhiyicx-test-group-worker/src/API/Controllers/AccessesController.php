<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;

class AccessesController
{
    /**
     * Get user GitHub accesses.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Packages\TestGroupWorker\Models\Access $model
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, AccessModel $model): JsonResponse
    {
        $user = $request->user();
        $accesses = $model->newQuery()->where('owner', $user->id)->get();

        return response()->json($accesses, 200);
    }

    public function show()
    {
    }

    public function store()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
