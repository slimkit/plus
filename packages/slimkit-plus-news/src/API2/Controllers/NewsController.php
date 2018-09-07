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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class NewsController extends Controller
{
    /**
     * 获取资讯列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $newsModel
     * @return json
     * @throws \Throwable
     */
    public function index(Request $request, News $newsModel)
    {
        $userId = $request->user('api')->id ?? 0;
        $id = array_values(
            array_filter(
                explode(',', $request->query('id', ''))
            )
        );
        $news = $newsModel
            ->query()
            ->orderBy('id', 'desc')
            ->when(! empty($id), function ($query) use ($id) {
                return $query->whereIn('id', $id);
            })
            ->when(empty($id), function ($query) use ($request) {
                return $query
                    ->when($recommend = (int) $request->query('recommend'), function ($query) use ($recommend) {
                        return $query->where('is_recommend', $recommend);
                    })
                    ->when($cateId = $request->query('cate_id'), function ($query) use ($cateId) {
                        return $query->where('cate_id', $cateId);
                    })
                    ->when($after = $request->query('after', 0), function ($query) use ($after) {
                        return $query->where('id', '<', $after);
                    })
                    ->when($key = $request->query('key'), function ($query) use ($key) {
                        return $query->where('title', 'like', '%'.$key.'%');
                    })
                    ->limit($request->query('limit', 15));
            })
            ->where('audit_status', 0)
            ->whereDoesntHave('blacks', function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->get();

        $data = $newsModel->getConnection()->transaction(function () use ($news, $userId) {
            return $news->each(function ($data) use ($userId) {
                $data->has_collect = $data->collected($userId);
                $data->has_like = $data->liked($userId);
                unset($data->pinned);
            });
        });

        return response()->json($data, 200);
    }

    /**
     * 获取一个分类的置顶资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param News     $newsModel
     * @param  Carbon  $datetime
     * @return json
     * @throws \Throwable
     */
    public function pinned(Request $request, News $newsModel, Carbon $datetime)
    {
        $user = $request->user('api')->id ?? 0;
        $cate = $request->query('cate_id');
        $news = $newsModel->where('news.audit_status', 0)
        ->join('news_pinneds', function ($join) use ($datetime) {
            return $join->on('news_pinneds.target', '=', 'news.id')->where('channel', 'news')->where('expires_at', '>', $datetime);
        })
        ->when($cate, function ($query) use ($cate) {
            return $query->where('news.cate_id', $cate);
        })
        ->select(['news.id', 'news.title', 'news.subject', 'news.created_at', 'news.updated_at', 'news.storage', 'news.cate_id', 'news.from', 'news.author', 'news.user_id', 'news.hits', 'news.text_content'])
        ->orderBy('news_pinneds.amount', 'desc')
        ->orderBy('news_pinneds.created_at', 'desc')
        ->get();

        return response()->json($newsModel->getConnection()->transaction(function () use ($news, $user) {
            return $news->each(function ($data) use ($user) {
                $data->has_collect = $data->collected($user);
                $data->has_like = $data->liked($user);
                unset($data->pinned);
            });
        }), 200);
    }

    /**
     * Get single news info.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @param Carbon   $datetime
     * @return json
     * @throws \Throwable
     */
    public function detail(Request $request, News $news, Carbon $datetime)
    {
        if ($news->audit_status == 4) {
            abort(404);
        }

        $user = $request->user('api')->id ?? 0;

        $news = $news->getConnection()->transaction(function () use ($user, $news, $datetime) {
            $news->increment('hits', 1);
            $news->load('tags');
            $news->has_collect = $news->collected($user);
            $news->has_like = $news->liked($user);
            $news->is_pinned = ! (bool) $news->pinned()->where('state', 1)->where('expires_at', '>', $datetime)->get()->isEmpty();
            $news->addHidden(['images', 'pinned']);

            return $news;
        });

        return response()->json($news, 200);
    }

    /**
     * Get correlation news from single news by tags.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @return json
     * @throws \Throwable
     */
    public function correlation(Request $request, News $news)
    {
        $user = $request->user()->id ?? 0;
        $news->load('tags');
        $limit = $request->query('limit', 3);

        $news = $news->getConnection()->transaction(function () use ($news, $user, $limit) {
            // 优先取含有相同标签的资讯
            $datas = $news->whereHas('tags', function ($query) use ($news) {
                return $query->where(function ($query) use ($news) {
                    return $news->tags->map(function ($tag) use ($query) {
                        $query->orWhere('id', $tag->id);
                    });
                });
            })
            ->select(['id', 'title', 'subject', 'created_at', 'updated_at', 'storage', 'cate_id', 'from', 'author', 'user_id', 'hits', 'text_content'])
            ->where('audit_status', 0)
            ->where('id', '!=', $news->id)
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();

            // 不足设定条数查询全部列表补全
            if ($datas->count() < $limit) {
                $extras = News::where('audit_status', 0)
                    ->whereNotIn('id', $datas->pluck('id'))
                    ->where('id', '!=', $news->id)
                    ->select(['id', 'title', 'subject', 'created_at', 'updated_at', 'storage', 'cate_id', 'from', 'author', 'user_id', 'hits'])
                    ->take($limit - $datas->count())
                    ->get();
                if ($extras) {
                    $extras->map(function ($extra) use ($datas) {
                        $datas->push($extra);
                    });
                }
            }

            return $datas->each(function ($data) use ($user) {
                $data->has_collect = $data->collected($user);
                $data->has_like = $data->liked($user);
                unset($data->pinned);
            });
        });

        return response()->json($news, 200);
    }
}
