<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Collection;
use SlimKit\PlusQuestion\Models\Answer;
use Illuminate\Contracts\Routing\ResponseFactory;

class AnswerCollectController extends Controller
{
    /**
     * Collect an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function store(Request $request, Answer $answer, ResponseFactory $response)
    {
        $user = $request->user()->id;
        if ($answer->collected($user)) {
            return $response->json(['message' => [trans('plus-question::answers.collect.collected')]], 422);
        }

        $answer->collect($user);

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
        if (! $answer->collected($user)) {
            return $response->json(['message' => [trans('plus-question::answers.collect.not-collected')]], 422);
        }

        $answer->unCollect($user);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 204);
    }

    /**
     * A list of answer for collections.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function index(Request $request, ResponseFactory $response, Collection $collectionModel)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);
        $user = $request->user();

        $collections = $collectionModel->with(['collectible', 'collectible.question', 'collectible.onlookers' => function ($query) use ($user) {
            return $query->where('id', $user->id);
        }])
            ->where('collectible_type', 'question-answers')
            ->where('user_id', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json($collections->map(function ($collection) use ($user) {
            if ($collection->collectible) {
                // 如果是需要围观支付的答案
                if ($collection->collectible->question &&
                    $collection->collectible->question->look &&
                    $collection->collectible->invited &&
                    $user->id !== $collection->collectible->user_id &&
                    $collection->collectible->onlookers->isEmpty()
                ) {
                    $collection->collectible->could = false;
                    $collection->collectible->body = null;
                }

                // 如果为匿名回答且回答者不是当前用户
                if ($collection->collectible->anonymity && $collection->collectible->user_id !== $user->id) {
                    $collection->collectible->user_id = 0;
                }
                $collection->collectible->liked = $collection->collectible->liked($user);
                $collection->collectible->addHidden('question', 'onlookers');
            }

            return $collection;
            
        }))->setStatusCode(200);
    }
}
