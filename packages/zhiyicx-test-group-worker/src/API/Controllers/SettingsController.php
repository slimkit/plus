<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Zhiyi\Plus\Packages\TestGroupWorker\Models\Access as AccessModel;

class SettingsController
{
    /**
     * Get all setting data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $access = $this->getAccessQuery()->find($user->id);
        $settings = [
            'github-access' => $access->login ?? null,
        ];

        return response()->json($settings, 200);
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
