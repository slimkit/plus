<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\Plus\API2\Requests\Group\ListAllSimplePosts;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\Plus\API2\Resources\Group\SimplePost as SimplePostResource;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;
use Zhiyi\Plus\API2\Resources\Comment as CommentResource;

class Post extends Controller
{
    use DateTimeToIso8601ZuluString;

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
            throw new AccessDeniedHttpException('你无权进行操作');
        }

        $post->excellent_at = $post->excellent_at ? null : new Carbon();
        $post->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function previewPosts(Request $request, GroupModel $group): JsonResponse
    {
        $posts = $group
            ->posts()
            ->with(['images'])
            ->whereNotNull('excellent_at')
            ->limit(2)
            ->orderBy('id', 'desc')
            ->get();
        $count = $posts->count();
        if ($count < 2) {
            $newPosts = $group
                ->posts()
                ->with(['images'])
                ->when($ids = $posts->pluck('id')->all(), function ($query) use ($ids) {
                    return $query->whereNotIn('id', $ids);
                })
                ->orderBy(GroupModel::UPDATED_AT, 'desc')
                ->limit(2 - $count)
                ->get();
            $posts = $posts->merge($newPosts->all());
        }

        $posts = $posts->map(function (PostModel $post) {
            $post->load(['comments' => function ($query) {
                return $query
                    ->limit(3)
                    ->orderBy('id', 'desc');
            }]);

            return [
                'id' => $post->id,
                'group_id' => $post->group_id,
                'user_id' => $post->user_id,
                'title' => $post->title,
                'summary' => $post->summary,
                'likes_count' => $post->likes_count,
                'comments_count' => $post->comments_count,
                'views_count' => $post->views_count,
                'created_at' => $this->dateTimeToIso8601ZuluString($post->{PostModel::CREATED_AT}),
                'excellent_at' => $post->excellent_at ? $this->dateTimeToIso8601ZuluString($post->excellent_at) : null,
                'images' => $post->images->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'size' => $item->size,
                    ];
                }),
                'comments' => CommentResource::collection($post->comments),
            ];
        });

        return new JsonResponse($posts, Response::HTTP_OK);
    }
}
