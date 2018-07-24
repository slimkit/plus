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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Plus\findMarkdownImageIDs;
use Zhiyi\Plus\Concerns\FindMarkdownFileTrait;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use function zhiyi\Component\ZhiyiPlus\PlusComponentNews\getShort;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;

/**
 * 后台资讯管理.
 */
class NewsController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * 资讯列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getNewsList(Request $request, Carbon $datetime)
    {
        $cate_id = $request->cate_id ?? '';
        // $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $key = $request->key;
        $state = $request->state ?? null;
        $recommend = $request->recommend == 'true' ? 1 : 0;

        $datas = News::where(function ($query) use ($key) {
            if ($key) {
                return $query->where('news.title', 'like', '%'.$key.'%');
            }
        })
            ->when($cate_id > 0, function ($query) use ($cate_id) {
                return $query->where('cate_id', $cate_id);
            })
            ->when($recommend, function ($query) use ($recommend) {
                return $query->where('is_recommend', $recommend);
            })
            ->where(function ($query) use ($state) {
                if ($state !== null) {
                    $query->where('news.audit_status', $state);
                }
            })
            ->whereIn('audit_status', [0, 1, 2, 3])
            ->orderBy('id', 'desc')
            ->with(['user', 'tags', 'pinned' => function ($query) use ($datetime) {
                return $query->whereDate('expires_at', '>=', $datetime);
            }])
            ->paginate($limit);

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * 回收站资讯列表.
     *
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getRecycleList(Request $request)
    {
        $cate_id = $request->cate_id ?? '';
        $limit = $request->limit ?? 15;
        $key = $request->key;

        $datas = News::where(function ($query) use ($key) {
            if ($key) {
                $query->where('news.title', 'like', '%'.$key.'%');
            }
        })
            ->when($cate_id > 0, function ($query) use ($cate_id) {
                $query->where('cate_id', $cate_id);
            })
            ->whereIn('audit_status', [4, 5])
            ->orderBy('id', 'desc')
            ->with('user', 'tags')
            ->paginate($limit);

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * @param Request  $request
     * @param TagModel $tagModel
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function doSaveNews(Request $request, TagModel $tagModel)
    {
        $content = $request->input('content');
        if (mb_strlen($content, 'utf8') > 10000) {
            return response()->json(['message' => ['内容不能大于10000字']], 422);
        }

        $tags = $tagModel->whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if (! $tags) {
            return response()->json(['message' => '填写的标签不存在或已删除'], 422);
        }
        $images = $this->findMarkdownImageNotWithModels($content ?: '');

        $allImages = findMarkdownImageIDs($content ?: '');
        $allImages = FileWith::whereIn('id', $allImages)
            ->orderByRaw("FIELD(id, '".implode("','", $allImages)."')")
            ->get();
        $formatImages = $allImages->map(function ($item) {
            return [
                'id' => $item->id,
                'width' => $item->file->height,
                'height' => $item->file->height,
                'mime' => $item->file->mime,
            ];
        });
        $news = null;
        if ($request->news_id) {
            $news = News::where('id', $request->news_id)->first();
            if ($news) {
                $news->title = $request->title;
                $news->subject = $request->subject ?: getShort($content, 60);
                $news->content = $content;
                $news->storage = $request->storage;
                $news->from = $request->from ?: '原创';
                $news->cate_id = $request->cate_id;
                $news->author = $request->author;
                $news->audit_status = 0;
                $news->images = $formatImages;

                $news->save();
                if ($request->storage) {
                    $images->push(FileWith::where('id', $request->storage)
                        ->whereNull('channel')
                        ->whereNull('raw')
                        ->first());
                }
                $news->getConnection()->transaction(function () use ($news, $images, $tags) {
                    $news->tags()->detach();
                    $news->tags()->attach($tags);
                    $this->resolveFileWith($news, $images);
                });

                return response()->json($news->id)->setStatusCode(201);
            }
        } else {
            $news = new News();
            $news->title = $request->title;
            $news->subject = $request->subject ?: getShort($content, 60);
            $news->user_id = $request->user()->id;
            $news->content = $content;
            $news->storage = $request->storage;
            $news->from = $request->from ?: '原创';
            $news->cate_id = $request->cate_id;
            $news->author = $request->author;
            $news->images = $formatImages;
            $news->save();
            if ($request->storage) {
                $images->push(FileWith::find($request->storage));
            }
            $news->getConnection()->transaction(function () use ($news, $images, $tags) {
                $news->tags()->attach($tags);
                $this->resolveFileWith($news, $images);
            });

            return response()->json($news->id)->setStatusCode(201);
        }
    }

    protected function resolveFileWith(News $news, $fileWiths)
    {
        $fileWiths->filter()->each(function (FileWith $fileWith) use ($news) {
            $fileWith->channel = 'news:image';
            $fileWith->raw = $news->id;
            $fileWith->save();
        });
    }

    public function recommend(News $news)
    {
        $news->is_recommend = abs($news->is_recommend - 1);
        $news->save();

        return response()->json(['message' => ['操作成功']])->setStatusCode(201);
    }

    public function auditNews(Request $request, int $news_id)
    {
        $news = News::find($news_id);
        if ($news) {
            $news->audit_status = $request->state;

            switch ($request->state) {
                case 3:
                    $channel = 'news:reject';
                    $message = sprintf('您的资讯《%s》已被驳回', $news->title);
                    $news->audit_count = $news->audit_count + 1;

                    $news->getConnection()->transaction(function () use ($news) {
                        if ($news->audit_count >= 3 && $news->contribute_amount > 0) {  // 驳回三次退款
                            $charge = new WalletChargeModel();

                            $charge->user_id = $news->user_id;
                            $charge->channel = 'system';
                            $charge->action = 0;
                            $charge->amount = $news->contribute_amount;
                            $charge->subject = '退还资讯投稿费用';
                            $charge->body = sprintf('退还资讯《%s》的投稿费用', $news->title);
                            $charge->status = 1;

                            $news->user->wallet->increment('balance', $charge->amount);
                            $charge->save();
                        }

                        $news->save();
                    });

                    break;
                case 0:
                    $channel = 'news:audit';
                    $message = sprintf('您的资讯《%s》已被审核通过', $news->title);
                    $news->save();
            }
            //发送通知
            $news->user->sendNotifyMessage($channel, $message, [
                'news' => $news,
            ]);
            $userCount = UserCountModel::firstOrNew([
                'type' => 'user-system',
                'user_id' => $news->user_id,
            ]);
            $userCount->total += 1;
            $userCount->save();

            return response()->json(['message' => ['操作成功']])->setStatusCode(204);
        }
    }

    public function delNews(Request $request, int $news_id)
    {
        $is_del = $request->is_del ?? 0;
        $news = News::find($news_id);
        if (! $news) {
            return response()->json(['message' => ['资讯不存在或已删除']]);
        }

        if (! $is_del) {
            $news->audit_status = 4;
            $news->save();

            return response()->json(['message' => ['已添加到回收站']], 204);
        } else {
            DB::transaction(function () use ($news, $news_id) {
                $news->delete();
                NewsCollection::where('news_id', $news_id)->delete();
            });

            return response()->json(['message' => ['删除成功']], 204);
        }
    }

    public function getNews(int $news_id)
    {
        $news = News::with('tags')->withTrashed()->find($news_id);

        return response()->json($news)->setStatusCode(200);
    }
}
