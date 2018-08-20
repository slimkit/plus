<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Controllers\Group;

use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\Plus\API2\Requests\Group\ListAllSimplePosts;
use Zhiyi\Plus\API2\Responses\Group\SimplePost as SimplePostResource;

class Post extends Controller
{
    public function simpleList(ListAllSimplePosts $request, PostModel $model): JsonResponse
    {
        $ids = array_filter(
            explode(',', $request->query('id', ''))
        );
        $ids = array_values($ids);

        $posts = $model
            ->query()
            ->with(['images'])
            ->whereIn('id', $ids)
            ->get();

        return SimplePostResource::collection($posts)
            ->toResponse($request)
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
