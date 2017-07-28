<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Tag as TagModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TagUserController extends Controller
{
    /**
     * Get all tags of the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response)
    {
        return $response->json(
            $request->user()->tags
        )->setStatusCode(200);
    }

    /**
     * Attach a tag for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\Tag $tag
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, TagModel $tag)
    {
        $user = $request->user();
        if ($user->tags()->newPivotStatementForId($tag->id)->first()) {
            return $response->json([
                'message' => [
                    trans('tag.user.attached', [
                        'tag' => $tag->name,
                    ]),
                ]
            ], 422);
        }

        $user->tags()->attach($tag);

        return $response->make('', 204);
    }
}
