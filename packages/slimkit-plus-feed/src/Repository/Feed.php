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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Repository;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use function Zhiyi\Plus\setting;

class Feed
{
    protected $model;
    /**
     * Cache repository.
     *
     * @var CacheContract
     */
    protected $cache;
    protected $dateTime;
    protected $limit;

    /**
     * Create the cash type respositorie.
     *
     * @param  CacheContract  $cache
     * @param  FeedModel  $model
     * @param  Carbon  $dateTime
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(
        CacheContract $cache,
        FeedModel $model,
        Carbon $dateTime
    ) {
        $this->limit = (int) setting('feed', 'pay-word-limit', 50);
        $this->cache = $cache;
        $this->model = $model;
        $this->dateTime = $dateTime;
    }

    /**
     * Find feed.
     *
     * @param  int  $id
     * @param  array  $columns
     *
     * @return FeedModel
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function find($id, $columns = ['*'])
    {
        $this->model = $this->model
            ->with('user')
            ->findOrFail($id, $columns);

        return $this->model;
    }

    /**
     * Feed images.
     *
     * @return Collection|static[]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function images()
    {
        $this->model->setRelation(
            'images',
            $this->cache
                ->remember(sprintf('feed:%s:images', $this->model->id),
                    $this->dateTime->copy()->addDays(7),
                    function () {
                        $this->model->load([
                            'images' => function (hasMany $hasOne) {
                                $hasOne->with('file');
                            }, ]
                        );

                        return $this->model->images;
                    }));

        return $this->model->images;
    }

    /**
     * preview likes.
     *
     * @return FeedModel
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function previewLike()
    {
        $minutes = $this->dateTime->copy()->addDays(1);
        $cacheKey = sprintf('feed:%s:preview-likes', $this->model->id);

        return $this->model->setRelation('likes',
            $this->cache->remember($cacheKey, $minutes, function () {
                if (! $this->model->relationLoaded('likes')) {
                    $this->model->load([
                        'likes' => function ($query) {
                            $query->limit(8)->orderBy('id', 'desc');
                        },
                    ]);
                }

                return $this->model->likes;
            }));
    }

    /**
     * Format feed data.
     *
     * @param  int  $user
     *
     * @return FeedModel
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function format(int $user = 0): FeedModel
    {
        $this->model->setRelation('images',
            $this->model->images->map(function (FileWithModel $item) use ($user
            ) {
                $image = [
                    'file' => $item->id,
                    'size' => $item->size,
                    'mime' => $item->file->mime ?? '',
                ];
                if ($item->paidNode !== null) {
                    $image['amount'] = $item->paidNode->amount;
                    $image['type'] = $item->paidNode->extra;
                    $image['paid'] = $user ? $item->paidNode->paid($user) :
                        false;
                    $image['paid_node'] = $item->paidNode->id;
                }

                return $image;
            }));

        // 动态收费
        if ($this->model->paidNode !== null) {
            $paidNode = [
                'paid'   => $this->model->paidNode->paid($user),
                'node'   => $this->model->paidNode->id,
                'amount' => $this->model->paidNode->amount,
            ];
            unset($this->model->paidNode);
            $this->model->paid_node = $paidNode;

            // 动态内容截取
            if (! $this->model->paid_node['paid']
                && $this->model->user_id != $user
            ) {
                $this->model->feed_content
                    = mb_substr($this->model->feed_content, 0, $this->limit);
            }
        }

        return $this->model;
    }

    /**
     * Set feed model.
     *
     * @param  FeedModel  $model
     *
     * @return Feed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setModel(FeedModel $model)
    {
        $this->model = $model;

        return $this;
    }

    public function forget($key)
    {
        $this->cache->forget($key);
    }

    /**
     * Ask the feed list of comments data, give priority to return to the top comments.
     *
     * @return FeedModel
     * @author bs<414606094@qq.com>
     */
    public function previewComments()
    {
        $comments = collect([]);
        $pinnedComments = $this->model->pinnedComments;

        if ($pinnedComments->count() < 5) {
            $ids = $pinnedComments->pluck('id')->filter()->all();
            $comments = $this->model->comments->filter(function ($comment) use ($ids) {
                return ! in_array($comment->id, $ids);
            });
        }

        $this->model->comments = $pinnedComments->map(function ($comment) {
            $comment->pinned = true;
            $comment->makeHidden('pivot');

            return $comment;
        })->merge($comments);
        $this->model->makeHidden('pinnedComments');

        return $this->model;
    }
}
