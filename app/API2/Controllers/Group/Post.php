<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\Plus\API2\Requests\Group\ListAllSimplePosts;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\Plus\API2\Resources\Group\SimplePost as SimplePostResource;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Post extends Controller
{
    public function __construct()
    {
        $this
            ->middleware('auth:api')
            ->only(['toggleExcellent']);
    }

    /**
     * List all simple posts.
     *
     * @param \Zhiyi\Plus\API2\Requests\Group\ListAllSimplePosts $request
     * @param \Zhiyi\PlsuGroup\Models\Post $model
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Toggle a post excellent.
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\PlusGroup\Models\GroupMember $memberModel
     * @param \Zhiyi\PlusGroup\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function toggleExcellent(Request $request, GroupMemberModel $memberModel, PostModel $post): Response
    {
        $user = $request->user();
        $canOperation = $memberModel
            ->query()
            ->where('group_id', $post->group_id)
            ->where('user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('role', 'founder')
                    ->orWhere('role', 'administrator');
            })
            ->where('disabled', 0)
            ->exists();
        if (! $canOperation) {
            return new AccessDeniedHttpException('你无权进行操作');
        }

        $post->excellent_at = $post->excellent_at ? null : new Carbon();
        $post->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
