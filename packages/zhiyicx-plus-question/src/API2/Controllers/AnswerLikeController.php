<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Services\Push;
use SlimKit\PlusQuestion\Models\Answer;
use Illuminate\Contracts\Routing\ResponseFactory;

class AnswerLikeController extends Controller
{
    /**
     * Like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function store(Request $request, Answer $answer, ResponseFactory $response)
    {
        $user = $request->user();
        if ($answer->liked($user)) {
            return $response->json(['message' => [trans('plus-question::answers.like.liked')]], 422);
        }

        $answer->like($user);
        $answer->user->extra()->firstOrCreate([])->increment('likes_count', 1);
        if ($answer->user_id !== $user->id) {
            $answer->user->unreadCount()->firstOrCreate([])->increment('unread_likes_count', 1);
            app(push::class)->push(sprintf('%s点赞了你的回答', $user->name), (string) $answer->user->id, ['channel' => 'question-answer:like']);
        }

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }

    /**
     * Cancel like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function destroy(Request $request, Answer $answer, ResponseFactory $response)
    {
        $user = $request->user()->id;
        if (! $answer->liked($user)) {
            return $response->json(['message' => [trans('plus-question::answers.like.not-liked')]], 422);
        }

        $answer->unlike($user);
        $answer->user->extra()->decrement('likes_count', 1);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 204);
    }

    /**
     * A list of users who like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function index(Request $request, Answer $answer, ResponseFactory $response)
    {
        $userID = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $likes = $answer->likes()->with('user')->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })->take($limit)->orderBy('id', 'desc')->get();

        return $response->json(
            $answer->getConnection()->transaction(function () use ($likes, $userID) {
                return $likes->map(function ($like) use ($userID) {
                    if (! $like->relationLoaded('user')) {
                        return $like;
                    }

                    $like->user->following = false;
                    $like->user->follower = false;

                    if ($userID && $like->user_id !== $userID) {
                        $like->user->following = $like->user->hasFollwing($userID);
                        $like->user->follower = $like->user->hasFollower($userID);
                    }

                    return $like;
                });
            })
        )->setStatusCode(200);
    }
}
