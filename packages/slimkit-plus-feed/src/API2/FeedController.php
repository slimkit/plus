<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Batch;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedPost as StoreFeedPostRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedVideo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed as FeedRepository;
use Zhiyi\Plus\AtMessage\AtMessageHelperTrait;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Zhiyi\Plus\Models\FeedTopicUserLink as FeedTopicUserLinkModel;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserCount;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use function Zhiyi\Plus\setting;

class FeedController extends Controller
{
    use AtMessageHelperTrait;

    /**
     * 分享列表.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(
        Request $request,
        ApplicationContract $app,
        ResponseContract $response
    ) {
        $type = $request->query('type', 'new');
        if (! in_array($type, ['new', 'hot', 'follow', 'users']) || $request->query('id', false)) {
            $type = 'new';
        }

        return $response->json([
            'pinned' => $app->call([$this, 'getPinnedFeeds']),
            'feeds' => $app->call([$this, $type]),
        ])
            ->setStatusCode(200);
    }

    public function getPinnedFeeds(
        Request $request,
        FeedModel $feedModel,
        FeedRepository $repository,
        Carbon $datetime
    ) {
        if ($request->query('after') || $request->query('hot')
            || $request->query('type') === 'follow'
            || $request->query('offset')
        ) {
            return collect([]);
        }

        $user = $request->user('api')->id ?? 0;
        // 置顶动态缓存
        $feeds = Cache::remember('pinnedFeeds', '5',
            function () use ($feedModel, $datetime, $user) {
                return $feedModel->newQuery()->select('feeds.*')
                    ->with([
                        'pinnedComments',
                        'comments' => function (MorphMany $builder) {
                            $builder->limit(10);
                        },
                        'user',
                    ])
                    ->join('feed_pinneds',
                        function (JoinClause $join) use ($datetime) {
                            return $join->on('feeds.id', '=',
                                'feed_pinneds.target')
                                ->where('channel', 'feed')
                                ->where('expires_at', '>', $datetime);
                        })
                    ->whereDoesntHave('blacks',
                        function (Builder $query) use ($user) {
                            $query->where('user_id', $user);
                        })
                    ->orderBy('feed_pinneds.amount', 'desc')
                    ->orderBy('feed_pinneds.created_at', 'desc')
                    ->get();
            });

        $user = $request->user('api')->id ?? 0;
        $updateValues = [];
        $feeds = $feeds->map(function (FeedModel $feed) use (
            $repository,
            $user
        ) {
            $feed->feed_view_count += 1;
            $updateValues[] = [
                'id' => $feed->id,
                'feed_view_count' => $feed->feed_view_count,
                'hot' => $feed->makeHotValue(),
            ];
            $repository->setModel($feed);
            $repository->images();
            $repository->format($user);
            $repository->previewComments();

            $feed->has_collect = $user ? $feed->collected($user) : false;
            $feed->has_like = $user ? $feed->liked($user) : false;

            return $feed;
        });

        Batch::update($feedModel, $updateValues, 'id');

        return $feeds;
    }

    /**
     * Get new feeds.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feedModel
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed  $repository
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function new(
        Request $request,
        FeedModel $feedModel,
        FeedRepository $repository
    ) {
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $user = $request->user('api')->id ?? 0;
        $search = $request->query('search');
        $id = $request->query('id', '');

        $feeds = $feedModel->newQuery()
            ->with([
                'pinnedComments',
                'comments' => function (MorphMany $builder) {
                    $builder->limit(10);
                },
                'user',
            ])
            ->when($after,
                function (Builder $query) use ($after) {
                    return $query->where('id', '<', $after);
                })
            ->when($id, function (Builder $query) use ($id) {
                $id = array_values(
                    array_filter(
                        explode(',', $id)
                    )
                );

                if (! $id) {
                    return $query;
                }

                return $query->whereIn('id', $id);
            })
            ->when(isset($search), function (Builder $query) use ($search) {
                return $query->where('feed_content', 'LIKE', '%'.$search.'%');
            })
            ->whereDoesntHave('blacks', function (Builder $query) use ($user) {
                $query->where('user_id', $user);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        $updateValues = [];

        $feeds = $feeds->map(function (FeedModel $feed) use (
            $repository,
            $user,
            &$updateValues
        ) {
            $feed->feed_view_count += 1;

            $updateValues[] = [
                'id' => $feed->id,
                'feed_view_count' => $feed->feed_view_count,
                'hot' => $feed->makeHotValue(),
            ];

            $repository->setModel($feed);
            $repository->images();
            $repository->format($user);
            $repository->previewComments();

            $feed->has_collect = $user ? $feed->collected($user) : false;
            $feed->has_like = $user ? $feed->liked($user) : false;

            return $feed;
        });

        count($updateValues) > 0 && Batch::update($feedModel, $updateValues, 'id');

        return $feeds;
    }

    /**
     * Get hot feeds.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg  $model
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed  $repository
     * @param  \Carbon\Carbon  $dateTime
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hot(
        Request $request,
        FeedRepository $repository,
        Carbon $dateTime,
        FeedModel $model
    ) {
        $hot = $request->query('hot', 0);
        $user = $request->user('api')->id ?? 0;

        $feeds = $model
            ->query()
            ->with([
                'pinnedComments',
                'user',
                'comments' => function (MorphMany $builder) {
                    $builder->limit(10);
                },
            ])
            ->when($hot, function (Builder $query) use ($hot) {
                return $query->where('hot', '<', $hot);
            })
            ->where(FeedModel::CREATED_AT, '>',
                $dateTime->subDay(setting('feed', 'list/hot-duration', 7)))
            ->limit($request->query('limit', 15))
            ->orderBy('hot', 'desc')
            ->get();
        $updateValues = [];

        $feeds = $feeds->map(function ($feed) use (
            $repository,
            $user,
            &$updateValues
        ) {
            $feed->feed_view_count += 1;

            $updateValues[] = [
                'id' => $feed->id,
                'feed_view_count' => $feed->feed_view_count,
                'hot' => $feed->makeHotValue(),
            ];

            $repository->setModel($feed);
            $repository->images();
            $repository->format($user);
            $repository->previewComments();
            $feed->has_collect = $user ? $feed->collected($user) : false;
            $feed->has_like = $user ? $feed->liked($user) : false;

            return $feed;
        });

        count($updateValues) > 0 && Batch::update($model, $updateValues, 'id');

        return $feeds;
    }

    /**
     * Get user follow user feeds.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $model
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed  $repository
     * @param  Carbon  $datetime
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function follow(
        Request $request,
        FeedModel $model,
        FeedRepository $repository
    ) {
        if (is_null($user = $request->user('api'))) {
            abort(401);
        }

        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $feeds = $model->newQuery()
            ->leftJoin('user_follow', function (JoinClause $join) use ($user) {
                $join->where('user_follow.user_id', $user->id);
            })
            ->with([
                'pinnedComments',
                'user',
                'comments' => function (MorphMany $builder) {
                    $builder->limit(10);
                },
            ])
            ->whereDoesntHave('blacks', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function (Builder $query) use ($user) {
                $query->whereColumn('feeds.user_id', '=', 'user_follow.target')
                    ->orWhere('feeds.user_id', $user->id);
            })
            ->when((bool) $after, function (Builder $query) use ($after) {
                return $query->where('feeds.id', '<', $after);
            })
            ->distinct()
            ->select('feeds.*')
            ->orderBy('feeds.id', 'desc')
            ->limit($limit)
            ->get();

        $updateValues = [];
        $feeds = $feeds->map(function (FeedModel $feed) use (
            $repository,
            $user,
            &$updateValues
        ) {
            $feed->feed_view_count += 1;
            $updateValues[] = [
                'id' => $feed->id,
                'feed_view_count' => $feed->feed_view_count,
                'hot' => $feed->makeHotValue(),
            ];

            $repository->setModel($feed);
            $repository->images();
            $repository->format($user->id);
            $repository->previewComments();

            $feed->has_collect = $feed->collected($user->id);
            $feed->has_like = $feed->liked($user->id);

            return $feed;
        });
        count($updateValues) > 0 && Batch::update($model, $updateValues, 'id');

        return $feeds;
    }

    /**
     * get single feed info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed  $repository
     * @param  int  $feed
     * @return mixed
     *
     * @throws Throwable
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(
        Request $request,
        FeedRepository $repository,
        int $feed
    ) {
        $user = $request->user('api')->id ?? 0;
        $feed = $repository->find($feed);
        if ($feed->paidNode !== null
            && $feed->paidNode->paid($user) === false
        ) {
            return response()->json([
                'message' => '请购买动态',
                'paid_node' => $feed->paidNode->id,
                'amount' => $feed->paidNode->amount,
            ])->setStatusCode(403);
        }

        // 启用获取事物，避免多次 sql 查询造成查询连接过多.
        return $feed->getConnection()->transaction(function () use (
            $feed,
            $repository,
            $user
        ) {
            $feed->has_collect = $feed->collected($user);
            $feed->has_like = $feed->liked($user);
            $feed->reward = $feed->rewardCount();

            $repository->images();
            $repository->previewLike();

            $feed->increment('feed_view_count');

            return response()->json($repository->format($user))
                ->setStatusCode(200);
        });
    }

    /**
     * Make feed link topics.
     *
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedPost  $request
     * @return array
     */
    private function makeFeedLinkTopics(StoreFeedPostRequest $request): array
    {
        $topics = array_map(function ($item): ?int {
            if (is_numeric($item) || $item == (int) $item) {
                return (int) $item;
            }

            throw new UnprocessableEntityHttpException('发布的话题存在非法数据');
        }, (array) $request->input('topics'));

        $topicsCount = count($topics);
        if ($topicsCount === 0) {
            return [];
        } elseif ($topicsCount > 5) {
            throw new UnprocessableEntityHttpException('话题最多允许五个');
        }

        $topics = array_values(array_filter($topics));
        $topicsModelIDs = (new FeedTopicModel)
            ->query()
            ->whereIn('id', $topics)
            ->select('id')
            ->get()
            ->pluck('id');

        if ($topicsModelIDs->diff($topics)->isNotEmpty()) {
            throw new UnprocessableEntityHttpException('不合法的话题数据，部分话题不存在');
        }

        return $topicsModelIDs->all();
    }

    /**
     * Link feed to topics and increment topic followers_count column.
     *
     * @param  array  $topics
     * @param  Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return void
     */
    private function linkFeedToTopics(array $topics, FeedModel $feed): void
    {
        if (empty($topics)) {
            return;
        }

        $feed->topics()->sync($topics);
        $query = (new FeedTopicModel)->query();
        $query->whereIn('id', $topics)->increment('feeds_count', 1);
    }

    /**
     * Update and touching user feed topics data.
     *
     * @param  \Zhiyi\Plus\Models\User  $user
     * @param  array  $topics
     * @return void
     */
    private function touchUserFollowBindFeedCount(
        UserModel $user,
        array $topics
    ): void {
        if (empty($topics)) {
            return;
        }

        foreach ($topics as $topic) {
            $topicID = $topic;
            if ($topic instanceof FeedTopicModel) {
                $topicID = $topic->id;
            }

            $link = $user
                ->feedTopics()
                ->newPivot()
                ->where('user_id', $user->id)
                ->where('topic_id', $topicID)
                ->first();
            if (! $link) {
                $link = new FeedTopicUserLinkModel();
                $link->topic_id = $topicID;
                $link->user_id = $user->id;
                $link->feeds_count = 0;
            }

            $link->feeds_count += 1;
            $link->save();
        }
    }

    /**
     * Create an feed.
     *
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedPost  $request
     * @return mixed
     */
    public function store(StoreFeedPostRequest $request)
    {
        $user = $request->user();
        $feed = $this->fillFeedBaseData($request, new FeedModel());
        $topics = $this->makeFeedLinkTopics($request);

        $paidNodes = $this->makePaidNode($request);
        $fileWiths = $this->makeFileWith($request);
        $videoWith = $this->makeVideoWith($request);
        $videoCoverWith = $this->makeVideoCoverWith($request);

        $response = $user->getConnection()->transaction(function () use (
            $request,
            $feed,
            $topics,
            $paidNodes,
            $fileWiths,
            $videoWith,
            $videoCoverWith,
            $user
        ) {
            $feed->save();
            $this->saveFeedPaidNode($request, $feed);
            $this->saveFeedFilePaidNode($paidNodes, $feed);
            $this->saveFeedFileWith($fileWiths, $feed);
            $this->linkFeedToTopics($topics, $feed);
            $this->touchUserFollowBindFeedCount($user, $topics);

            if ($videoWith) {
                $this->saveFeedVideoWith($videoWith, $videoCoverWith, $feed);
            }

            $user->extra()->firstOrCreate([])->increment('feeds_count', 1);

            return response()->json(['message' => '发布成功', 'id' => $feed->id])
                ->setStatusCode(201);
        });

        $this->sendAtMessage((string) $feed->feed_content, $user, $feed);

        return $response;
    }

    /**
     * 创建文件使用模型.
     *
     * @param  StoreFeedPostRequest  $request
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeFileWith(StoreFeedPostRequest $request)
    {
        return FileWithModel::whereIn(
            'id',
            collect($request->input('images'))->filter(function (array $item) {
                return isset($item['id']);
            })->map(function (array $item) {
                return $item['id'];
            })->values()
        )->where('channel', null)
            ->where('raw', null)
            ->where('user_id', $request->user()->id)
            ->get();
    }

    /**
     * 获取动态视频.
     *
     * @Author   Wayne
     * @DateTime 2018-04-02
     * @Email    qiaobin@zhiyicx.com
     *
     * @param  StoreFeedPostRequest  $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function makeVideoWith(StoreFeedPostRequest $request)
    {
        $video = $request->input('video');

        return ($video['video_id'] ?? null)
            ? FileWithModel::query()->where(
                'id',
                $video['video_id']
            )->where('channel', null)
                ->where('raw', null)
                ->where('user_id', $request->user()->id)
                ->first()
            : null;
    }

    /**
     * 获取段视频封面.
     *
     * @Author   Wayne
     * @DateTime 2018-04-02
     * @Email    qiaobin@zhiyicx.com
     *
     * @param  StoreFeedPostRequest  $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function makeVideoCoverWith(StoreFeedPostRequest $request)
    {
        $video = $request->input('video');

        return ($video['cover_id'] ?? null)
            ? FileWithModel::query()->where(
                'id',
                $video['cover_id']
            )->where('channel', null)
                ->where('raw', null)
                ->where('user_id', $request->user()->id)
                ->first()
            : null;
    }

    /**
     * 创建付费节点模型.
     *
     * @param  StoreFeedPostRequest  $request
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makePaidNode(StoreFeedPostRequest $request)
    {
        return collect($request->input('images'))->filter(function (array $item
        ) {
            return isset($item['amount']);
        })->map(function (array $item) {
            $paidNode = new PaidNodeModel();
            $paidNode->channel = 'file';
            $paidNode->raw = $item['id'];
            $paidNode->amount = $item['amount'];
            $paidNode->extra = $item['type'];

            return $paidNode;
        });
    }

    /**
     * 保存视频.
     *
     * @Author   Wayne
     * @DateTime 2018-04-02
     * @Email    qiaobin@zhiyicx.com
     *
     * @param $videoWith
     * @param $videoCoverWith
     * @param  FeedModel  $feed
     * @return void
     *
     * @throws \Throwable
     */
    protected function saveFeedVideoWith(
        $videoWith,
        $videoCoverWith,
        FeedModel $feed
    ) {
        $video = new FeedVideo();
        DB::transaction(function () use (
            $feed,
            $video,
            $videoWith,
            $videoCoverWith
        ) {
            $videoWith->channel = 'feed:video';
            $videoCoverWith->channel = 'feed:video:cover';
            $videoWith->raw = $feed->id;
            $videoCoverWith->raw = $feed->id;
            $videoWith->save();
            $videoCoverWith->save();

            $video->video_id = $videoWith->id;
            $video->cover_id = $videoCoverWith->id;
            $video->user_id = $feed->user_id;
            $video->feed_id = $feed->id;
            $video->width = $videoWith->file->width;
            $video->height = $videoWith->file->height;
            $video->save();
        });
    }

    /**
     * 保存分享图片使用.
     *
     * @param  array  $fileWiths
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveFeedFileWith($fileWiths, FeedModel $feed)
    {
        foreach ($fileWiths as $fileWith) {
            $fileWith->channel = 'feed:image';
            $fileWith->raw = $feed->id;
            $fileWith->save();
        }
    }

    /**
     * 保存分享文件付费节点.
     *
     * @param  array  $nodes
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveFeedFilePaidNode($nodes, FeedModel $feed)
    {
        foreach ($nodes as $node) {
            $node->subject = '动态图片';
            $node->body = '动态的图片';
            $node->user_id = $feed->user_id;
            $node->save();
        }
    }

    /**
     * 保存分享付费节点.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveFeedPaidNode(Request $request, FeedModel $feed)
    {
        $amount = $request->input('amount');

        if (! $amount) {
            return;
        }

        $paidNode = new PaidNodeModel();
        $paidNode->amount = $amount;
        $paidNode->channel = 'feeds';
        $paidNode->raw = $feed->id;
        $paidNode->subject = '动态';
        $paidNode->body = $paidNode->subject;
        $paidNode->user_id = $feed->user_id;
        $paidNode->save();
    }

    /**
     * Fill initial feed data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed  $feed
     * @return \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function fillFeedBaseData(Request $request, FeedModel $feed): FeedModel
    {
        $baseFormInputs = $request->only([
            'feed_content', 'feed_from', 'feed_mark',
            'feed_latitude', 'feed_longtitude', 'feed_geohash',
            'repostable_type', 'repostable_id',
        ]);
        foreach (array_filter($baseFormInputs) as $key => $value) {
            $feed->$key = $value;
        }

        $feed->feed_client_id = $request->ip();
        $feed->audit_status = 1;
        $feed->user_id = $request->user()->id;

        return $feed;
    }

    /**
     * 新版删除动态接口，如有置顶申请讲退还相应积分.
     *
     * @param  ResponseContract  $response
     * @param  FeedModel  $feed
     * @return mixed
     *
     * @throws Throwable
     *
     * @author BS <414606094@qq.com>
     */
    public function newDestroy(
        ResponseContract $response,
        FeedModel $feed
    ) {
        $user = $feed->user;
        $authUser = request()->user();
        if (! $user) {
            // 删除话题关联
            $feed->topics->each(function ($topic) {
                $topic->feeds_count -= 1;
                $topic->save();
            });
            $feed->topics()->sync([]);

            $feed->delete();

            return $response->json(null, 204);
        } elseif ($authUser->id !== $user->id
            && ! $authUser->ability('[feed] Delete Feed')
        ) {
            return $response->json(['message' => '你没有权限删除动态'])
                ->setStatusCode(403);
        }

        // 统计当前用户未操作的动态评论置顶
        $unReadCount = FeedPinned::query()->where('channel', 'comment')
            ->where('target_user', $user->id)
            ->whereNull('expires_at')
            ->count();

        $feed->getConnection()->transaction(function () use (
            $feed,
            $user,
            $unReadCount
        ) {
            $process = new UserProcess();
            $userCount = UserCount::firstOrNew([
                'type' => 'user-feed-comment-pinned',
                'user_id' => $user->id,
            ]);
            if ($pinned = $feed->pinned()->where('user_id', $user->id)
                ->where('expires_at', null)->first()
            ) { // 存在未审核的置顶申请时退款
                $process->reject(0, $pinned->amount, $user->id, '动态申请置顶退款',
                    sprintf('退还申请置顶动态《%s》的款项',
                        Str::limit($feed->feed_content, 100)));
            }
            $pinnedComments = $feed->pinnedingComments()
                ->get();
            $pinnedComments->map(function ($comment) use ($process, $feed) {
                $process->reject(0, $comment->amount, $comment->user_id,
                    '评论申请置顶退款', sprintf('退还在动态《%s》申请评论置顶的款项',
                        Str::limit($feed->feed_content, 100)));
                $comment->delete();
            });
            // 更新未被操作的评论置顶
            $userCount->total = $unReadCount - $pinnedComments->count();
            $userCount->save();

            // 删除话题关联
            $feed->topics->each(function ($topic) {
                $topic->feeds_count -= 1;
                $topic->save();
            });
            $feed->topics()->sync([]);

            $feed->delete();
            $user->extra()->decrement('feeds_count', 1);
        });

        return $response->json(null, 204);
    }

    /**
     * 获取某个用户的动态列表.
     *
     * @param  Request  $request
     * @param  FeedModel  $feedModel
     * @param  FeedRepository  $repository
     * @return mixed
     *
     * @author bs<414606094@qq.com>
     */
    public function users(
        Request $request,
        FeedModel $feedModel,
        FeedRepository $repository,
        Carbon $datetime
    ) {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $current_user = $request->query('user', $user);
        $screen = $request->query('screen');

        $feeds = $feedModel->where('user_id', $current_user)
            ->when($screen, function (Builder $query) use ($datetime, $screen) {
                switch ($screen) {
                    case 'pinned':
                        $query->whereHas('pinned',
                            function ($query) use ($datetime) {
                                $query->where('expires_at', '>', $datetime);
                            });
                        break;
                    case 'paid':
                        $query->whereHas('paidNode');
                        break;
                }
            })
            ->when($after, function (Builder $query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->with([
                'pinnedComments',
                'user',
            ])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
            $repository->setModel($feed);
            $repository->images();
            $repository->format($user);
            $repository->previewComments();

            $feed->has_collect = $user ? $feed->collected($user) : false;
            $feed->has_like = $user ? $feed->liked($user) : false;

            return $feed;
        });
    }
}
