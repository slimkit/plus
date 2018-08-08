<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\API2\Controllers\Feed;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use function Zhiyi\Plus\setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\Types\Models as ModelsTypes;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Zhiyi\Plus\API2\Resources\Feed\Topic as TopicResource;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Zhiyi\Plus\API2\Requests\Feed\TopicIndex as IndexRequest;
use Zhiyi\Plus\API2\Requests\Feed\EditTopic as EditTopicRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zhiyi\Plus\Models\FeedTopicUserLink as FeedTopicUserLinkModel;
use Zhiyi\Plus\API2\Requests\Feed\CreateTopic as CreateTopicRequest;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Zhiyi\Plus\API2\Resources\Feed\TopicCollection as TopicCollectionResource;

class Topic extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        // Add Auth(api) middleware.
        $this
            ->middleware('auth:api')
            ->only(['create', 'update']);

        // Add DisposeSensitive middleware.
        $this
            ->middleware('sensitive:name,desc')
            ->only(['create', 'update']);
    }

    public function listTopicsOnlyHot(FeedTopicModel $model): JsonResponse
    {
        $topics = $model
            ->query()
            ->whereNotNull('hot_at')
            ->where('status', FeedTopicModel::REVIEW_PASSED)
            ->limit(8)
            ->orderBy('id', 'desc')
            ->get();
        if (($count = $topics->count()) < 8) {
            $topics = $topics->merge(
                $model->query()
                ->whereNull('hot_at')
                ->where('status', FeedTopicModel::REVIEW_PASSED)
                ->limit(8 - $count)
                ->orderBy('feeds_count', 'desc')
                ->get()
                ->all()
            )->values();
        }

        return (new TopicCollectionResource($topics))
            ->response()
            ->setStatusCode(Response::HTTP_OK /* 200 */);
    }

    /**
     * List topics.
     *
     * @param \Zhiyi\Plus\Requests\Feed\TopicIndex $request
     * @param \Zhiyi\Plus\Models\FeedTopic $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, FeedTopicModel $model): JsonResponse
    {
        if ($request->query('only') === 'hot') {
            return $this->listTopicsOnlyHot($model);
        }

        // Get query data `id` order direction.
        // Value: `asc` or `desc`
        $direction = $request->query('direction', 'desc');

        // Query database data.
        $result = $model
            ->query()
            ->where('status', FeedTopicModel::REVIEW_PASSED)

            // If `$request->query('q')` param exists,
            // create "`name` like %?%" SQL where.
            ->when((bool) ($searchKeyword = $request->query('q', false)), function (EloquentBuilder $query) use ($searchKeyword) {
                return $query->where('name', 'like', sprintf('%%%s%%', $searchKeyword));
            })

            // If `$request->query('index)` param exists,
            // using `$direction` create "id ? ?" where
            //     ?[0] `$direction === asc` is `>`
            //     ?[0] `$direction === desc` is `<`
            ->when((bool) ($indexID = $request->query('index', false)), function (EloquentBuilder $query) use ($indexID, $direction) {
                return $query->where('id', $direction === 'asc' ? '>' : '<', $indexID);
            })

            // Set the number of data
            ->limit($request->query('limit', 15))

            // Using `$direction` set `id` direction,
            // the `$direction` enum `asc` or `desc`.
            ->orderBy('id', $direction)

            // Set only query table column name.
            ->select('id', 'name', 'logo', Model::CREATED_AT)

            // Run the SQL query, return a collection.
            // instanceof \Illuminate\Support\Collection
            ->get();

        // Create the action response.
        $response = (new TopicCollectionResource($result))
            ->response()
            ->setStatusCode(Response::HTTP_OK /* 200 */);

        return $response;
    }

    /**
     * Create an topic.
     *
     * @param \Zhiyi\Plus\API2\Requests\Feed\CreateTopic $request
     * @param \Zhiyi\Plus\Types\Models $types
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateTopicRequest $request, ModelsTypes $types): JsonResponse
    {
        // Create feed topic module
        $topic = new FeedTopicModel;
        foreach ($request->only(['name', 'logo', 'desc']) as $key => $value) {
            $topic->{$key} = $value;
        }

        // If logo exists, inspect file with ID to be used.
        $with = null;
        if ($topic->logo) {
            $with = (new FileWithModel)
                ->query()
                ->where('id', $topic->logo)
                ->first();
            if ($with->channel || $with->raw) {
                throw new UnprocessableEntityHttpException('Logo 文件不合法');
            }
        }

        // Database query `name` used
        $exists = $topic
            ->query()
            ->where('name', $topic->name)
            ->exists();
        if ($exists) {
            throw new UnprocessableEntityHttpException(sprintf('“%s”话题已存在', $topic->name));
        }

        // Fetch the authentication user model.
        $user = $request->user();

        // Open a database transaction,
        // database commit success return the topic model.
        $topic = $user->getConnection()->transaction(function () use ($user, $topic, $with, $types) {
            // Set topic creator user ID and
            // init default followers count.
            $topic->creator_user_id = $user->id;
            $topic->followers_count = 1;
            $topic->status = setting('feed', 'topic:need-review', false) ? FeedTopicModel::REVIEW_WAITING : FeedTopicModel::REVIEW_PASSED;
            $topic->save();

            // Attach the creator user follow the topic.
            $link = new FeedTopicUserLinkModel();
            $link->topic_id = $topic->id;
            $link->user_id = $user->id;
            $link->following_at = new Carbon();
            $link->save();

            // If the FileWith instance of `FileWithModel`,
            // set topic class alias to `channel`, set the
            // topic `id` to `raw` column.
            // Reset FileWith owner for the authenticated auth.
            if ($with instanceof FileWithModel) {
                $with->channel = $types->get(FeedTopicModel::class, ModelsTypes::KEY_BY_CLASSNAME);
                $with->raw = $topic->id;
                $with->user_id = $user->id;
                $with->save();
            }

            return $topic;
        });

        // Headers:
        //      Status: 201 Created
        // Body:
        //      { "id": $topid->id }
        return new JsonResponse(
            [
                'id' => $topic->id,
                'need_review' => setting('feed', 'topic:need-review', false),
            ],
            Response::HTTP_CREATED /* 201 */
        );
    }

    /**
     * Edit an topic.
     *
     * @param \Zhiyi\Plus\API2\Requests\Feed\EditTopic $request
     * @param \Zhiyi\Plus\Types\Models $types
     * @param \Zhiyi\Plus\Models\FeedTopic $topic
     * @return \Illuminate\Http\Response
     */
    public function update(EditTopicRequest $request, ModelsTypes $types, FeedTopicModel $topic): Response
    {
        $this->authorize('update', $topic);

        // Create success 204 response
        $response = (new Response())->setStatusCode(Response::HTTP_NO_CONTENT /* 204 */);

        // If `logo` and `desc` field all is NULL
        $with = null;
        $desc = $request->input('desc');
        $name = $request->input('name');
        if (! ($logo = (int) $request->input('logo')) && ! $desc && ! $name) {
            return $response;
        } elseif ($logo && $logo !== $topic->logo) {
            $with = (new FileWithModel)
                ->query()
                ->where('id', $logo)
                ->first();
            if ($with->channel || $with->raw) {
                throw new UnprocessableEntityHttpException('Logo 文件不合法');
            }

            $with->user_id = $request->user()->id;
            $with->channel = $types->get(FeedTopicModel::class, ModelsTypes::KEY_BY_CLASSNAME);
            $with->raw = $topic->id;
        }

        $topic->name = $name ?: $topic->name;
        $topic->desc = $desc ?: $topic->desc;

        return $topic->getConnection()->transaction(function () use ($response, $topic, $with): Response {
            if ($with instanceof FileWithModel) {
                $with->save();

                // Set file with ID to topic logo.
                $topic->logo = $with->id;
            }

            $topic->save();

            return $response;
        });
    }

    /**
     * Get a single topic.
     *
     * @param \Zhiyi\Plus\Models\FeedTopic $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FeedTopicModel $topic): JsonResponse
    {
        if ($topic->status !== FeedTopicModel::REVIEW_PASSED) {
            throw new NotFoundHttpException('话题不存在或者还没有通过审核');
        }

        $topic->participants = $topic
            ->users()
            ->newPivotStatement()
            ->where('topic_id', $topic->id)
            ->where('user_id', '!=', $topic->creator_user_id)
            ->orderBy(Model::UPDATED_AT, 'desc')
            ->limit(3)
            ->select('user_id')
            ->get()
            ->pluck('user_id');

        return (new TopicResource($topic))
            ->response()
            ->setStatusCode(Response::HTTP_OK /* 200 */);
    }
}
