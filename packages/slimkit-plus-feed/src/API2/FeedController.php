<?php

declare(strict_types=1);

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed as FeedRepository;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedPost as StoreFeedPostRequest;

class FeedController extends Controller
{
    /**
     * 分享列表.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ApplicationContract $app, ResponseContract $response)
    {
        $type = $request->query('type', 'new');

        if (! in_array($type, ['new', 'hot', 'follow', 'users'])) {
            $type = 'new';
        }

        return $response->json([
            'ad' => $app->call([$this, 'getAd']),
            'pinned' => $app->call([$this, 'getPinnedFeeds']),
            'feeds' => $app->call([$this, $type]),
        ])->setStatusCode(200);
    }

    public function getAd()
    {
        // todo.
    }

    public function getPinnedFeeds(Request $request, FeedModel $feedModel, FeedRepository $repository, Carbon $datetime)
    {
        if ($request->query('after')) {
            return collect([]);
        }

        $feeds = $feedModel->select('feeds.*')
            ->join('feed_pinneds', function ($join) use ($datetime) {
                return $join->on('feeds.id', '=', 'feed_pinneds.target')->where('channel', 'feed')->where('expires_at', '>', $datetime);
            })
            ->with([
                'pinnedComments' => function ($query) use ($datetime) {
                    return $query->where('expires_at', '>', $datetime)->limit(5);
                },
                'user',
            ])
            ->orderBy('feeds.id', 'desc')
            ->get();

        $user = $request->user('api')->id ?? 0;

        return $feedModel->getConnection()->transaction(function () use ($feeds, $repository, $user) {
            return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
                $repository->setModel($feed);
                $repository->images();
                $repository->format($user);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user);
                $feed->has_like = $feed->liked($user);

                return $feed;
            });
        });
    }

    /**
     * Get new feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feedModel
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed $repository
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function new(Request $request, FeedModel $feedModel, FeedRepository $repository, Carbon $datetime)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $user = $request->user('api')->id ?? 0;
        $search = $request->query('search');

        $feeds = $feedModel->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })->when(isset($search), function ($query) use ($search) {
            return $query->where('feed_content', 'LIKE', '%'.$search.'%');
        })
        ->orderBy('id', 'desc')
        ->with([
            'pinnedComments' => function ($query) use ($datetime) {
                return $query->with('user')->where('expires_at', '>', $datetime)->limit(5);
            },
            'user',
        ])
        ->limit($limit)
        ->get();

        return $feedModel->getConnection()->transaction(function () use ($feeds, $repository, $user) {
            return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
                $repository->setModel($feed);
                $repository->images();
                $repository->format($user);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user);
                $feed->has_like = $feed->liked($user);

                return $feed;
            });
        });
    }

    /**
     * Get hot feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg $model
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed $repository
     * @param \Carbon\Carbon $dateTime
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hot(Request $request, LikeModel $model, FeedRepository $repository, Carbon $dateTime)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $user = $request->user('api')->id ?? 0;

        $ids = $model->where('likeable_type', 'feeds')
            ->join('feeds', function ($query) {
                $query->on('feeds.id', '=', 'likes.likeable_id');
            })
            ->select('likeable_id', $model->getConnection()->raw('COUNT(likes.id) as count'))
            ->where('likes.created_at', '>', $dateTime->subMonth())
            ->when((bool) $after, function ($query) use ($after) {
                return $query->where('likes.likeable_id', '<', $after);
            })
            ->groupBy('likeable_id')
            ->orderBy('likeable_id', 'desc')
            ->limit($limit)
            ->pluck('likeable_id');

        $feeds = FeedModel::whereIn('id', $ids)
            ->with([
                'pinnedComments' => function ($query) use ($dateTime) {
                    return $query->with('user')->where('expires_at', '>', $dateTime)->limit(5);
                },
                'user',
            ])
            ->orderBy('id', 'desc')
            ->get();

        return $model->getConnection()->transaction(function () use ($feeds, $repository, $user) {
            return $feeds->map(function ($feed) use ($repository, $user) {
                if (! $feed) {
                    return null;
                }

                $repository->setModel($feed);
                $repository->images();
                $repository->format($user);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user);
                $feed->has_like = $feed->liked($user);

                return $feed;
            });
        });
    }

    /**
     * Get user follow user feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed $repository
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function follow(Request $request, FeedModel $model, FeedRepository $repository, Carbon $datetime)
    {
        if (is_null($user = $request->user('api'))) {
            abort(401);
        }

        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $feeds = $model->leftJoin('user_follow', function ($join) use ($user) {
            $join->where('user_follow.user_id', $user->id);
        })
        ->where(function ($query) use ($user) {
            $query->whereColumn('feeds.user_id', '=', 'user_follow.target')
                ->orWhere('feeds.user_id', $user->id);
        })
        ->with([
            'pinnedComments' => function ($query) use ($datetime) {
                return $query->with('user')->where('expires_at', '>', $datetime)->limit(5);
            },
            'user',
        ])
        ->when((bool) $after, function ($query) use ($after) {
            return $query->where('feeds.id', '<', $after);
        })
        ->distinct()
        ->select('feeds.*')
        ->orderBy('feeds.id', 'desc')
        ->limit($limit)
        ->get();

        return $model->getConnection()->transaction(function () use ($repository, $user, $feeds) {
            return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
                $repository->setModel($feed);
                $repository->images();
                $repository->format($user->id);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user->id);
                $feed->has_like = $feed->liked($user);

                return $feed;
            });
        });
    }

    /**
     * get single feed info.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository\Feed $repository
     * @param int $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, FeedRepository $repository, int $feed)
    {
        $user = $request->user('api')->id ?? 0;
        $feed = $repository->find($feed);

        if ($feed->paidNode !== null && $feed->paidNode->paid($user) === false) {
            return response()->json([
                'message' => ['请购买动态'],
                'paid_node' => $feed->paidNode->id,
                'amount' => $feed->paidNode->amount,
            ])->setStatusCode(403);
        }

        // 启用获取事物，避免多次 sql 查询造成查询连接过多.
        return $feed->getConnection()->transaction(function () use ($feed, $repository, $user) {
            $feed->has_collect = $feed->collected($user);
            $feed->has_like = $feed->liked($user);
            $feed->reward = $feed->rewardCount();

            $repository->images();
            $repository->previewLike();

            $feed->increment('feed_view_count');

            return response()->json($repository->format($user))->setStatusCode(200);
        });
    }

    /**
     * 储存分享.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedPost $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreFeedPostRequest $request)
    {
        $user = $request->user();
        $feed = $this->fillFeedBaseData($request, new FeedModel());

        $paidNodes = $this->makePaidNode($request);
        $fileWiths = $this->makeFileWith($request);

        try {
            $feed->saveOrFail();
            $feed->getConnection()->transaction(function () use ($request, $feed, $paidNodes, $fileWiths, $user) {
                $this->saveFeedPaidNode($request, $feed);
                $this->saveFeedFilePaidNode($paidNodes, $feed);
                $this->saveFeedFileWith($fileWiths, $feed);
                $user->extra()->firstOrCreate([])->increment('feeds_count', 1);
            });
        } catch (\Exception $e) {
            $feed->delete();
            throw $e;
        }

        return response()->json(['message' => ['发布成功'], 'id' => $feed->id])->setStatusCode(201);
    }

    /**
     * 创建文件使用模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
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
     * 创建付费节点模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makePaidNode(StoreFeedPostRequest $request)
    {
        return collect($request->input('images'))->filter(function (array $item) {
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
     * 保存分享图片使用.
     *
     * @param array $fileWiths
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return void
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
     * @param array $nodes
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveFeedFilePaidNode($nodes, FeedModel $feed)
    {
        foreach ($nodes as $node) {
            $node->subject = '购买动态附件';
            $node->body = sprintf('购买动态《%s》的图片', str_limit($feed->feed_content, 100, '...'));
            $node->user_id = $feed->user_id;
            $node->save();
        }
    }

    /**
     * 保存分享付费节点.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return void
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
        $paidNode->channel = 'feed';
        $paidNode->raw = $feed->id;
        $paidNode->subject = sprintf('购买动态《%s》', str_limit($feed->feed_content, 100, '...'));
        $paidNode->body = $paidNode->subject;
        $paidNode->user_id = $feed->user_id;
        $paidNode->save();
    }

    /**
     * Fill initial feed data.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function fillFeedBaseData(Request $request, FeedModel $feed): FeedModel
    {
        foreach ($request->only(['feed_content', 'feed_from', 'feed_mark', 'feed_latitude', 'feed_longtitude', 'feed_geohash']) as $key => $value) {
            $feed->$key = $value;
        }

        $feed->feed_client_id = $request->ip();
        $feed->audit_status = 1;
        $feed->user_id = $request->user()->id;

        return $feed;
    }

    /**
     * Delete comment.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @param FeedRepository $repository
     * @param FeedModel $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request,
                            ResponseContract $response,
                            FeedModel $feed)
    {
        $user = $request->user();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => '你没有权限删除动态'])->setStatusCode(403);
        }

        $feed->getConnection()->transaction(function () use ($feed, $user) {
            if ($pinned = $feed->pinned()->where('user_id', $user->id)->where('expires_at', null)->first()) { // 存在未审核的置顶申请时退款
                $charge = new WalletChargeModel();
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = 0;
                $charge->action = 1;
                $charge->amount = $pinned->amount;
                $charge->subject = '动态申请置顶退款';
                $charge->body = sprintf('退还申请置顶动态《%s》的款项', str_limit($feed->feed_content, 100));
                $charge->status = 1;

                $user->wallet()->increment('balance', $charge->amount);
                $user->walletCharges()->save($charge);
                $pinned->delete();
            }

            $feed->delete();
            $user->extra()->decrement('feeds_count', 1);
        });

        return $response->json(null, 204);
    }

    /**
     * 新版删除动态接口，如有置顶申请讲退还相应积分.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @param FeedModel $feed
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function newDestroy(Request $request,
                            ResponseContract $response,
                            FeedModel $feed)
    {
        $user = $request->user();

        if ($user->id !== $feed->user_id) {
            return $response->json(['message' => '你没有权限删除动态'])->setStatusCode(403);
        }

        $feed->getConnection()->transaction(function () use ($feed, $user) {
            if ($pinned = $feed->pinned()->where('user_id', $user->id)->where('expires_at', null)->first()) { // 存在未审核的置顶申请时退款

                $process = new UserProcess();
                $process->reject(0, $pinned->amount, $user->id, '动态申请置顶退款', sprintf('退还申请置顶动态《%s》的款项', str_limit($feed->feed_content, 100)));
            }

            $feed->delete();
            $user->extra()->decrement('feeds_count', 1);
        });

        return $response->json(null, 204);
    }

    /**
     * 获取某个用户的动态列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request        $request
     * @param  FeedModel      $feedModel
     * @param  FeedRepository $repository
     * @return mixed
     */
    public function users(Request $request, FeedModel $feedModel, FeedRepository $repository, Carbon $datetime)
    {
        $user = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 15);
        $after = $request->query('after');
        $current_user = $request->query('user', $user);
        $screen = $request->query('screen');

        $feeds = $feedModel->where('user_id', $current_user)
            ->when($screen, function ($query) use ($datetime, $screen) {
                switch ($screen) {
                    case 'pinned':
                        $query->whereHas('pinned', function ($query) use ($datetime) {
                            $query->where('expires_at', '>', $datetime);
                        });
                        break;
                    case 'paid':
                        $query->whereHas('paidNode');
                        break;
                }
            })
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->with(['pinnedComments' => function ($query) use ($datetime) {
                return $query->where('expires_at', '>', $datetime)->limit(5);
            }, 'user'])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return $feedModel->getConnection()->transaction(function () use ($feeds, $repository, $user) {
            return $feeds->map(function (FeedModel $feed) use ($repository, $user) {
                $repository->setModel($feed);
                $repository->images();
                $repository->format($user);
                $repository->previewComments();

                $feed->has_collect = $feed->collected($user);
                $feed->has_like = $feed->liked($user);

                return $feed;
            });
        });
    }
}
